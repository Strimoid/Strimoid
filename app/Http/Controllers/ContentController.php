<?php namespace Strimoid\Http\Controllers;

use App;
use Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Input;
use Queue;
use Redirect;
use Response;
use Route;
use Rss;
use Session;
use Setting;
use Strimoid\Contracts\Repositories\ContentRepository;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Content;
use Strimoid\Models\Group;
use Summon\Summon;
use Validator;

class ContentController extends BaseController
{
    use ValidatesRequests;

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

    /**
     * @param ContentRepository $contents
     * @param GroupRepository   $groups
     * @param FolderRepository  $folders
     */
    public function __construct(ContentRepository $contents,
        GroupRepository $groups, FolderRepository $folders)
    {
        $this->contents = $contents;
        $this->groups = $groups;
        $this->folders = $folders;
    }

    /**
     * Display contents from given group.
     *
     * @param string $groupName
     *
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function showContentsFromGroup($groupName = null)
    {
        $routeName = Route::currentRouteName();
        $tab = str_contains($routeName, 'new') ? 'new' : 'popular';

        // If user is on homepage, then use proper group
        if (! Route::input('groupname')) {
            $groupName = $this->homepageGroup();
        }

        // Make it possible to browse everything by adding all parameter
        if (Input::get('all')) {
            $tab = null;
        }

        $group = $this->groups->requireByName($groupName);
        view()->share('group', $group);

        $canSortBy = ['comments_count', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(Input::get('sort'), $canSortBy) ? Input::get('sort') : null;

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

        $folder = $this->folders->getByName($userName, $folderName);
        view()->share('folder', $folder);

        if (! $folder->canBrowse()) {
            App::abort(404);
        }

        $canSortBy = ['comments', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(Input::get('sort'), $canSortBy) ? Input::get('sort') : null;

        $builder = $folder->contents($tab, $orderBy);

        return $this->showContents($builder);
    }

    protected function showContents($builder)
    {
        $builder->with('group', 'user');

        if (Auth::check()) $builder->with('usave', 'vote');

        $this->filterByTime($builder, Input::get('time'));

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
        if (! $days) {
            return;
        }

        $builder->fromDaysAgo($days);
    }

    /**
     * Generate RSS feed from given collection of contents.
     *
     * @param $contents
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

    /**
     * Show content comments.
     *
     * @param Content $content
     *
     * @return \Illuminate\View\View
     */
    public function showComments($content)
    {
        $sortBy = Input::get('sort');

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

    /**
     * Show content add form.
     *
     * @return \Illuminate\View\View
     */
    public function showAddForm()
    {
        return view('content.add');
    }

    /**
     * Show content edit form.
     *
     * @param Content $content
     *
     * @return \Illuminate\View\View
     */
    public function showEditForm(Content $content)
    {
        if (!$content->canEdit(Auth::user())) {
            return Redirect::route('content_comments', $content->getKey())
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        return view('content.edit', compact('content'));
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function addContent(Request $request)
    {
        $rules = [
            'title'       => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'groupname'   => 'required|exists:groups,urlname',
        ];

        if (Input::get('type') == 'link') {
            $rules['url'] = 'required|url_custom|max:2048';
        } else {
            $rules['text'] = 'required|min:1|max:50000';
        }

        $this->validate($request, $rules);

        $group = Group::name(Input::get('groupname'))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group)) {
            return Redirect::action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Zostałeś zbanowany w wybranej grupie');
        }

        if ($group->type == 'announcements'
            && !Auth::user()->isModerator($group)) {
            return Redirect::action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać treści do wybranej grupy');
        }

        $content = new Content(Input::only([
            'title', 'description', 'nsfw', 'eng',
        ]));

        if (Input::get('type') == 'link') {
            $content->url = Input::get('url');
        } else {
            $content->text = Input::get('text');
        }

        $content->user()->associate(Auth::user());
        $content->group()->associate($group);

        // Download thumbnail in background to don't waste user time
        //if (Input::get('thumbnail') == 'on')
        //    $content->thumbnail_loading = true;

        $content->save();

        if ($content->thumbnail_loading) {
            Queue::push('Strimoid\Handlers\DownloadThumbnail', [
                'id' => $content->getKey(),
            ]);
        }

        return Redirect::route('content_comments');
    }

    /**
     * @param Content $content
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function editContent($content)
    {
        if (!$content->canEdit(Auth::user())) {
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

        $content->title = Input::get('title');
        $content->description = Input::get('description');

        if ($content->text) {
            $content->text = Input::get('text');
        } else {
            $content->url = Input::get('url');
        }

        $content->nsfw = Input::get('nsfw');
        $content->eng = Input::get('eng');

        $content->save();

        return Redirect::route('content_comments', $content->getKey());
    }

    /**
     * @param Content $content
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeContent($content = null)
    {
        $content = $content ?: Content::findOrFail(Input::get('id'));

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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function softRemoveContent()
    {
        $content = Content::findOrFail(Input::get('id'));

        if ($content->canRemove(Auth::user())) {
            $content->deletedBy()->associate(Auth::user());
            $content->save();

            $content->delete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    /**
     * @param Content $content
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEmbedCode($content)
    {
        $embedCode = $content->getEmbed();

        return Response::json(['status' => 'ok', 'code' => $embedCode]);
    }

    /**
     * @param Content $content
     *
     * @return \Illuminate\View\View
     */
    public function chooseThumbnail($content)
    {
        if (!$content->canEdit(Auth::user())) {
            return Redirect::route('content_comments', $content->getKey())
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        $thumbnails = [];

        try {
            $summon = new Summon($content->getURL());
            $thumbnails = $summon->fetch();
        } catch (\Exception $e) {
        }

        $websiteThumbnail = 'http://img.bitpixels.com/getthumbnail?code=74491&size=100&url='.urlencode($content->url);
        $thumbnails['thumbnails'][] = $websiteThumbnail;

        Session::put('thumbnails', $thumbnails['thumbnails']);

        return view('content.thumbnails', compact('content', 'thumbnails'));
    }

    /**
     * @return mixed
     */
    public function saveThumbnail()
    {
        $content = Content::findOrFail(Input::get('id'));
        $thumbnails = Session::get('thumbnails');

        if (!$content->canEdit(Auth::user())) {
            return Redirect::route('content_comments', $content->getKey())
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        $index = (int) Input::get('thumbnail');

        if (Input::has('thumbnail') && isset($thumbnails[$index])) {
            $content->setThumbnail($thumbnails[$index]);
        } else {
            $content->removeThumbnail();
        }

        return Redirect::route('content_comments', $content->getKey());
    }
}
