<?php namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Input;
use Queue;
use Redirect;
use Response;
use Route;
use Setting;
use Strimoid\Contracts\Repositories\ContentRepository;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Content;
use Strimoid\Models\Group;
use Validator;

class ContentController extends BaseController
{
    /**
     * @var ContentRepository
     */
    protected $contents;

    /**
     * @var GroupRepository
     */
    protected $groups;

    /**
     * @var FolderRepository
     */
    protected $folders;

    public function __construct(
        ContentRepository $contents,
        GroupRepository $groups,
        FolderRepository $folders
    ) {
        $this->contents = $contents;
        $this->groups = $groups;
        $this->folders = $folders;
    }

    /**
     * Display contents from given group.

     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function showContentsFromGroup(string $groupName = null)
    {
        $routeName = Route::currentRouteName();
        $tab = str_contains($routeName, 'new') ? 'new' : 'popular';

        // If user is on homepage, then use proper group
        if (!Route::input('groupname')) {
            $groupName = $this->homepageGroup();
        }

        // Make it possible to browse everything by adding all parameter
        if (request('all')) {
            $tab = null;
        }

        $group = $this->groups->requireByName($groupName);
        view()->share('group', $group);

        if (auth()->guest() && $group->isPrivate) {
            return redirect()->guest('login');
        }

        $canSortBy = ['comments_count', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(request('sort'), $canSortBy) ? request('sort') : null;

        $builder = $group->contents($tab, $orderBy);

        return $this->showContents($builder);
    }

    /**
     * Display contents from given folder.
     *
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function showContentsFromFolder()
    {
        $tab = str_contains(Route::currentRouteName(), 'new') ? 'new' : 'popular';

        $userName = Route::input('user') ?: Auth::id();
        $folderName = Route::input('folder');

        $folder = $this->folders->requireByName($userName, $folderName);
        view()->share('folder', $folder);

        if (!$folder->canBrowse()) {
            abort(404);
        }

        $canSortBy = ['comments', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(request('sort'), $canSortBy) ? request('sort') : null;

        $builder = $folder->contents($tab, $orderBy);

        return $this->showContents($builder);
    }

    protected function showContents($builder)
    {
        $builder->with('group', 'user');

        if (Auth::check()) {
            $builder->with('usave', 'vote');
        }

        $this->filterByTime($builder, request('time'));

        // Paginate and attach parameters to paginator links
        $perPage = Setting::get('entries_per_page', 25);
        $contents = $builder->paginate($perPage);
        $contents->appends(Input::only(['sort', 'time', 'all']));

        // Return RSS feed for some of routes
        if (ends_with(Route::currentRouteName(), '_rss')) {
            return $this->generateRssFeed($contents);
        }

        return view('content.display', compact('contents'));
    }

    protected function filterByTime($builder, $days)
    {
        if (!$days) {
            return;
        }

        $builder->fromDaysAgo($days);
    }

    /**
     * Generate RSS feed from given collection of contents.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function generateRssFeed($contents)
    {
        return response()
            ->view('content.rss', compact('contents'))
            ->header('Content-Type', 'text/xml')
            ->setTtl(60);
    }

    public function showComments(Content $content) : View
    {
        $sortBy = request('sort');

        if (in_array($sortBy, ['uv', 'replies'])) {
            $content->comments = $content->comments->sortBy(function ($comment) use ($sortBy) {
                return ($sortBy == 'uv') ? $comment->uv : $comment->replies->count();
            })->reverse();
        }

        view()->share('group', $content->group);

        return view('content.comments', compact('content'));
    }

    public function showFrame(Content $content)
    {
        return view('content.frame', compact('content'));
    }

    public function showAddForm() : View
    {
        return view('content.add');
    }

    public function showEditForm(Content $content)
    {
        if (!$content->canEdit(user())) {
            return Redirect::route('content_comments', $content->getKey())
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        return view('content.edit', compact('content'));
    }

    public function addContent(Request $request)
    {
        $rules = [
            'title'       => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'groupname'   => 'required|exists:groups,urlname',
        ];

        if (request('type') == 'link') {
            $rules['url'] = 'required|url_custom|max:2048';
        } else {
            $rules['text'] = 'required|min:1|max:50000';
        }

        $this->validate($request, $rules);

        $group = Group::name(request('groupname'))->firstOrFail();
        $group->checkAccess();

        if (user()->isBanned($group)) {
            return Redirect::action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Zostałeś zbanowany w wybranej grupie');
        }

        if ($group->type == 'announcements'
            && !user()->isModerator($group)) {
            return Redirect::action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać treści do wybranej grupy');
        }

        $content = new Content(Input::only([
            'title', 'description', 'nsfw', 'eng',
        ]));

        if (request('type') == 'link') {
            $content->url = request('url');
        } else {
            $content->text = request('text');
        }

        $content->user()->associate(user());
        $content->group()->associate($group);

        $content->save();

        if (request('thumbnail') == 'on') {
            Queue::push('Strimoid\Handlers\DownloadThumbnail', [
                'id' => $content->getKey(),
            ]);
        }

        return Redirect::route('content_comments', $content);
    }

    public function editContent(Content $content)
    {
        if (!$content->canEdit(user())) {
            return Redirect::route('content_comments', $content->getKey())
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        $rules = [
            'title'       => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
        ];

        if ($content->text) {
            $rules['text'] = 'required|min:1|max:50000';
        } else {
            $rules['url'] = 'required|url_custom|max:2048';
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::action('ContentController@showEditForm', $content->getKey())
                ->withInput()
                ->withErrors($validator);
        }

        $data = request()->only(['title', 'description', 'nsfw', 'eng']);
        $content->fill($data);

        if ($content->text) {
            $content->text = request('text');
        } else {
            $content->url = request('url');
        }

        $content->save();

        return Redirect::route('content_comments', $content);
    }

    public function removeContent(Content $content = null)
    {
        $id = hashids_decode(request('id'));
        $content = $content ?: Content::findOrFail($id);

        if ($content->created_at->diffInMinutes() > 60) {
            return Response::json([
                'status' => 'error',
                'error'  => 'Minął dozwolony czas na usunięcie treści.',
            ]);
        }

        if (Auth::id() === $content->user_id) {
            $content->forceDelete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json([
            'status' => 'error',
            'error'  => 'Nie masz uprawnień do usunięcia tej treści.',
        ]);
    }

    public function softRemoveContent()
    {
        $id = hashids_decode(request('id'));
        $content = Content::findOrFail($id);

        if ($content->canRemove(user())) {
            $content->deletedBy()->associate(user());
            $content->save();

            $content->delete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }
}
