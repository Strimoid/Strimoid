<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Strimoid\Settings\Facades\Setting;
use Strimoid\Contracts\Repositories\ContentRepository;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Content;
use Strimoid\Models\Group;
use Illuminate\Support\Facades\Validator;
use Strimoid\Handlers\DownloadThumbnail;

class ContentController extends BaseController
{
    protected ContentRepository $contents;

    protected GroupRepository $groups;

    protected FolderRepository $folders;

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
     */
    public function showContentsFromGroup(Request $request, string $groupName = null)
    {
        $routeName = Route::currentRouteName();
        $tab = Str::contains($routeName, 'new') ? 'new' : 'popular';

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

        if ($group->isPrivate && auth()->guest()) {
            return redirect()->guest('login');
        }

        $canSortBy = ['comments_count', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(request('sort'), $canSortBy) ? request('sort') : null;

        $builder = $group->contents($tab, $orderBy);

        return $this->showContents($request, $builder);
    }

    /**
     * Display contents from given folder.
     */
    public function showContentsFromFolder(Request $request)
    {
        $tab = Str::contains(Route::currentRouteName(), 'new') ? 'new' : 'popular';

        $user = Route::input('user') ?: user();
        $folderName = Route::input('folder');

        $folder = $this->folders->requireByName($user->name, $folderName);
        view()->share('folder', $folder);

        if (!$folder->canBrowse()) {
            abort(404);
        }

        $canSortBy = ['comments', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(request('sort'), $canSortBy) ? request('sort') : null;

        $builder = $folder->contents($tab, $orderBy);

        return $this->showContents($request, $builder);
    }

    protected function showContents(Request $request, $builder)
    {
        $builder->with('group', 'user');

        if (Auth::check()) {
            $builder->with('userSave', 'vote');
        }

        $this->filterByTime($builder, request('time'));

        // Paginate and attach parameters to paginator links
        $perPage = Setting::get('entries_per_page');
        $contents = $builder->paginate($perPage);
        $contents->appends($request->only(['sort', 'time', 'all']));

        // Return RSS feed for some of routes
        if (Str::endsWith(Route::currentRouteName(), '_rss')) {
            return $this->generateRssFeed($contents);
        }

        return view('content.display', compact('contents'));
    }

    protected function filterByTime($builder, $days): void
    {
        if (!$days) {
            return;
        }

        $builder->fromDaysAgo($days);
    }

    /**
     * Generate RSS feed from given collection of contents.
     */
    protected function generateRssFeed($contents): \Symfony\Component\HttpFoundation\Response
    {
        return response()
            ->view('content.rss', compact('contents'))
            ->header('Content-Type', 'text/xml')
            ->setTtl(60);
    }

    public function showComments(Content $content): View
    {
        $sortBy = request('sort');

        if (in_array($sortBy, ['uv', 'replies'])) {
            $content->comments = $content->comments->sortBy(fn ($comment) => $sortBy === 'uv' ? $comment->uv : $comment->replies->count())->reverse();
        }

        view()->share('group', $content->group);

        return view('content.comments', compact('content'));
    }

    public function showFrame(Content $content)
    {
        return view('content.frame', compact('content'));
    }

    public function showAddForm(): View
    {
        return view('content.add');
    }

    public function showEditForm(Content $content)
    {
        $policyDecision = Gate::inspect('edit', $content);

        if ($policyDecision->denied()) {
            return redirect()
                ->route('content_comments', $content->getKey())
                ->with('danger_msg', $policyDecision->message());
        }

        return view('content.edit', compact('content'));
    }

    public function addContent(Request $request): RedirectResponse
    {
        $rules = [
            'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'groupname' => 'required|exists:groups,urlname',
        ];

        if (request('type') === 'link') {
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

        if ($group->type === 'announcements'
            && !user()->isModerator($group)) {
            return Redirect::action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać treści do wybranej grupy');
        }

        $content = new Content($request->only([
            'title', 'description', 'nsfw', 'eng',
        ]));

        if (request('type') === 'link') {
            $content->url = request('url');
        } else {
            $content->text = request('text');
        }

        $content->user()->associate(user());
        $content->group()->associate($group);

        $content->save();

        if (request('thumbnail') === 'on') {
            Queue::push(DownloadThumbnail::class, [
                'id' => $content->getKey(),
            ]);
        }

        return Redirect::route('content_comments', $content);
    }

    public function editContent(Request $request, Content $content): RedirectResponse
    {
        $policyDecision = Gate::inspect('edit', $content);

        if ($policyDecision->denied()) {
            return redirect()
                ->route('content_comments', $content->getKey())
                ->with('danger_msg', $policyDecision->message());
        }

        $rules = [
            'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
        ];

        if ($content->text) {
            $rules['text'] = 'required|min:1|max:50000';
        } else {
            $rules['url'] = 'required|url_custom|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

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

    public function removeContent(Content $content = null): JsonResponse
    {
        $id = hashids_decode(request('id'));
        $content = $content ?: Content::findOrFail($id);

        $this->authorize('remove', $content);

        if ($content->forceDelete()) {
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    public function softRemoveContent(): JsonResponse
    {
        $id = hashids_decode(request('id'));
        $content = Content::findOrFail($id);

        $this->authorize('softRemove', $content);

        $content->deletedBy()->associate(user());
        $content->save();

        $content->delete();

        return Response::json(['status' => 'ok']);
    }
}
