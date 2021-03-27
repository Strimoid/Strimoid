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
    public function __construct(protected ContentRepository $contents, protected GroupRepository $groups, protected FolderRepository $folders, private \Illuminate\Routing\Router $router, private \Illuminate\Contracts\View\Factory $viewFactory, private \Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Routing\Redirector $redirector, private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private \Illuminate\Contracts\Auth\Access\Gate $gate, private \Illuminate\Queue\QueueManager $queueManager, private \Illuminate\Validation\Factory $validationFactory)
    {
    }

    /**
     * Display contents from given group.
     */
    public function showContentsFromGroup(Request $request, string $groupName = null)
    {
        $routeName = $this->router->currentRouteName();
        $tab = Str::contains($routeName, 'new') ? 'new' : 'popular';

        // If user is on homepage, then use proper group
        if (!$this->router->input('groupname')) {
            $groupName = $this->homepageGroup();
        }

        // Make it possible to browse everything by adding all parameter
        if ($request->input('all')) {
            $tab = null;
        }

        $group = $this->groups->requireByName($groupName);
        $this->viewFactory->share('group', $group);

        if ($group->isPrivate && $this->guard->guest()) {
            return $this->redirector->guest('login');
        }

        $canSortBy = ['comments_count', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array($request->input('sort'), $canSortBy) ? $request->input('sort') : null;

        $builder = $group->contents($tab, $orderBy);

        return $this->showContents($request, $builder);
    }

    /**
     * Display contents from given folder.
     */
    public function showContentsFromFolder(Request $request)
    {
        $tab = Str::contains($this->router->currentRouteName(), 'new') ? 'new' : 'popular';

        $user = $this->router->input('user') ?: user();
        $folderName = $this->router->input('folder');

        $folder = $this->folders->requireByName($user->name, $folderName);
        $this->viewFactory->share('folder', $folder);

        if (!$folder->canBrowse()) {
            abort(404);
        }

        $canSortBy = ['comments', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array($request->input('sort'), $canSortBy) ? $request->input('sort') : null;

        $builder = $folder->contents($tab, $orderBy);

        return $this->showContents($request, $builder);
    }

    protected function showContents(Request $request, $builder)
    {
        $builder->with('group', 'user');

        if ($this->authManager->check()) {
            $builder->with('userSave', 'vote');
        }

        $this->filterByTime($builder, $request->input('time'));

        // Paginate and attach parameters to paginator links
        $perPage = Setting::get('entries_per_page');
        $contents = $builder->paginate($perPage);
        $contents->appends($request->only(['sort', 'time', 'all']));

        // Return RSS feed for some of routes
        if (Str::endsWith($this->router->currentRouteName(), '_rss')) {
            return $this->generateRssFeed($contents);
        }

        return $this->viewFactory->make('content.display', compact('contents'));
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
        return $this->responseFactory
            ->view('content.rss', compact('contents'))
            ->header('Content-Type', 'text/xml')
            ->setTtl(60);
    }

    public function showComments(Content $content, \Illuminate\Http\Request $request): View
    {
        $sortBy = $request->input('sort');

        if (in_array($sortBy, ['uv', 'replies'])) {
            $content->comments = $content->comments->sortBy(fn ($comment) => $sortBy === 'uv' ? $comment->uv : $comment->replies->count())->reverse();
        }

        $this->viewFactory->share('group', $content->group);

        return $this->viewFactory->make('content.comments', compact('content'));
    }

    public function showFrame(Content $content)
    {
        return $this->viewFactory->make('content.frame', compact('content'));
    }

    public function showAddForm(): View
    {
        return $this->viewFactory->make('content.add');
    }

    public function showEditForm(Content $content)
    {
        $policyDecision = $this->gate->inspect('edit', $content);

        if ($policyDecision->denied()) {
            return $this->redirector
                ->route('content_comments', $content->getKey())
                ->with('danger_msg', $policyDecision->message());
        }

        return $this->viewFactory->make('content.edit', compact('content'));
    }

    public function addContent(Request $request): RedirectResponse
    {
        $rules = [
            'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'groupname' => 'required|exists:groups,urlname',
        ];

        if ($request->input('type') === 'link') {
            $rules['url'] = 'required|url_custom|max:2048';
        } else {
            $rules['text'] = 'required|min:1|max:50000';
        }

        $this->validate($request, $rules);

        $group = Group::name($request->input('groupname'))->firstOrFail();
        $group->checkAccess();

        if (user()->isBanned($group)) {
            return $this->redirector->action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Zostałeś zbanowany w wybranej grupie');
        }

        if ($group->type === 'announcements'
            && !user()->isModerator($group)) {
            return $this->redirector->action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać treści do wybranej grupy');
        }

        $content = new Content($request->only([
            'title', 'description', 'nsfw', 'eng',
        ]));

        if ($request->input('type') === 'link') {
            $content->url = $request->input('url');
        } else {
            $content->text = $request->input('text');
        }

        $content->user()->associate(user());
        $content->group()->associate($group);

        $content->save();

        if ($request->input('thumbnail') === 'on') {
            $this->queueManager->push(DownloadThumbnail::class, [
                'id' => $content->getKey(),
            ]);
        }

        return $this->redirector->route('content_comments', $content);
    }

    public function editContent(Request $request, Content $content): RedirectResponse
    {
        $policyDecision = $this->gate->inspect('edit', $content);

        if ($policyDecision->denied()) {
            return $this->redirector
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

        $validator = $this->validationFactory->make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->redirector->action('ContentController@showEditForm', $content->getKey())
                ->withInput()
                ->withErrors($validator);
        }

        $data = $request->only(['title', 'description', 'nsfw', 'eng']);
        $content->fill($data);

        if ($content->text) {
            $content->text = $request->input('text');
        } else {
            $content->url = $request->input('url');
        }

        $content->save();

        return $this->redirector->route('content_comments', $content);
    }

    public function removeContent(Content $content = null, \Illuminate\Http\Request $request): JsonResponse
    {
        $id = hashids_decode($request->input('id'));
        $content = $content ?: Content::findOrFail($id);

        $this->authorize('remove', $content);

        if ($content->forceDelete()) {
            return $this->responseFactory->json(['status' => 'ok']);
        }

        return $this->responseFactory->json(['status' => 'error']);
    }

    public function softRemoveContent(\Illuminate\Http\Request $request): JsonResponse
    {
        $id = hashids_decode($request->input('id'));
        $content = Content::findOrFail($id);

        $this->authorize('softRemove', $content);

        $content->deletedBy()->associate(user());
        $content->save();

        $content->delete();

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
