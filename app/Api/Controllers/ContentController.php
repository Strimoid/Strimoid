<?php

namespace Strimoid\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Content;
use Strimoid\Models\Group;
use Strimoid\Models\User;
use Strimoid\Handlers\DownloadThumbnail;

class ContentController extends BaseController
{
    protected FolderRepository $folders;

    protected GroupRepository $groups;

    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->folders = $folders;
        $this->groups = $groups;
    }

    public function index(Request $request)
    {
        if ($request->has('folder')) {
            $username = request('user', auth()->id());
            $entity = $this->folders->getByName($username, request('folder'));
        } else {
            $groupName = request('group', 'all');
            $entity = $this->groups->requireByName($groupName);
        }

        $type = request('type', 'all');
        $canSortBy = ['comments', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(request('sort'), $canSortBy)
            ? request('sort')
            : null;

        $builder = $entity->contents($type, $orderBy)->with('group', 'user');

        // Time filter
        $time = request('time');
        if ($time) {
            $builder->fromDaysAgo($time);
        }

        // Domain filter
        $domain = request('domain');
        if ($domain) {
            $builder->where('domain', $domain);
        }

        // User filter
        if ($request->has('user')) {
            $user = User::name(request('user'))->firstOrFail();
            $builder->where('user_id', $user->getKey());
        }

        $perPage = $request->has('per_page')
            ? between(request('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    public function show(Content $content): Content
    {
        $content->load([
            'related', 'comments', 'comments.user', 'comments.replies', 'group', 'user',
        ]);

        return $content;
    }

    public function store(Request $request): JsonResponse
    {
        $rules = [
            'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'group' => 'required|exists:groups,urlname',
        ];

        if (request('text')) {
            $rules['text'] = 'required|min:1|max:50000';
        } else {
            $rules['url'] = 'required|url_custom';
        }

        $this->validate($request, $rules);

        $group = Group::name(request('group'))->firstOrFail();
        $group->checkAccess();

        if (user()->isBanned($group)) {
            return response()->json([
                'status' => 'error',
                'error' => 'Użytkownik został zbanowany w wybranej grupie.',
            ], 400);
        }

        if ($group->type === 'announcements' && !user()->isModerator($group)) {
            return response()->json([
                'status' => 'error',
                'error' => 'Użytkownik nie może dodawać treści w tej grupie.',
            ], 400);
        }

        $content = new Content($request->only([
            'title', 'description', 'nsfw', 'eng',
        ]));

        if (request('text')) {
            $content->text = request('text');
        } else {
            $content->url = request('url');
        }

        $content->user()->associate(user());
        $content->group()->associate($group);

        $content->save();

        // Download thumbnail in background to don't waste user time
        $thumbnail = $request->get('thumbnail');

        if ($thumbnail !== 'false' && $thumbnail !== 'off') {
            $content->thumbnail_loading = true;
            Queue::push(DownloadThumbnail::class, [
                'id' => $content->getKey(),
            ]);
        }

        return response()->json([
            'status' => 'ok', '_id' => $content->getKey(), 'content' => $content,
        ]);
    }

    public function edit(Request $request, Content $content): JsonResponse
    {
        $policyDecision = Gate::inspect('edit', $content);

        if ($policyDecision->denied()) {
            return response()->json([
                'status' => 'error', 'error' => $policyDecision->message(),
            ], 400);
        }

        $rules = [
            'title' => 'min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
        ];

        if ($content->text) {
            $rules['text'] = 'min:1|max:50000';
        } else {
            $rules['url'] = 'url_custom|max:2048';
        }

        $this->validate($request, $rules);

        $fields = ['title', 'description', 'nsfw', 'eng'];
        $fields[] = $content->text ? 'text' : 'url';

        $content->update($request->only($fields));

        return response()->json(['status' => 'ok']);
    }
}
