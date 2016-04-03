<?php namespace Strimoid\Api\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Queue;
use Response;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Content;
use Strimoid\Models\Group;
use Strimoid\Models\User;

class ContentController extends BaseController
{
    /**
     * @var FolderRepository
     */
    protected $folders;

    /**
     * @var GroupRepository
     */
    protected $groups;

    /**
     * @param FolderRepository $folders
     * @param GroupRepository  $groups
     */
    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->folders = $folders;
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (Input::has('folder')) {
            $username = Input::get('user', Auth::id());
            $entity = $this->folders->getByName($username, Input::get('folder'));
        } else {
            $groupName = Input::get('group', 'all');
            $entity = $this->groups->requireByName($groupName);
        }

        $type = Input::get('type', 'all');
        $canSortBy = ['comments', 'uv', 'created_at', 'frontpage_at'];
        $orderBy = in_array(Input::get('sort'), $canSortBy)
            ? Input::get('sort')
            : null;

        $builder = $entity->contents($type, $orderBy)->with('group', 'user');

        // Time filter
        $time = Input::get('time');
        if ($time) {
            $builder->fromDaysAgo($time);
        }

        // Domain filter
        $domain = Input::get('domain');
        if ($domain) {
            $builder->where('domain', $domain);
        }

        // User filter
        if (Input::has('user')) {
            $user = User::name(Input::get('user'))->firstOrFail();
            $builder->where('user_id', $user->getKey());
        }

        $perPage = Input::has('per_page')
            ? between(Input::get('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    /**
     * @param Content $content
     *
     * @return Content
     */
    public function show(Content $content)
    {
        $content->load([
            'related', 'comments', 'comments.user', 'comments.replies', 'group', 'user',
        ]);

        return $content;
    }

    /**
     * Add new content.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $rules = [
            'title'       => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'group'       => 'required|exists:groups,urlname',
        ];

        if (Input::get('text')) {
            $rules['text'] = 'required|min:1|max:50000';
        } else {
            $rules['url'] = 'required|url_custom';
        }

        $this->validate($request, $rules);

        $group = Group::name(Input::get('group'))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group)) {
            return Response::json([
                'status' => 'error',
                'error'  => 'Użytkownik został zbanowany w wybranej grupie.',
            ], 400);
        }

        if ($group->type == 'announcements' && ! Auth::user()->isModerator($group)) {
            return Response::json([
                'status' => 'error',
                'error'  => 'Użytkownik nie może dodawać treści w tej grupie.',
            ], 400);
        }

        $content = new Content($request->only([
            'title', 'description', 'nsfw', 'eng',
        ]));

        if (Input::get('text')) {
            $content->text = Input::get('text');
        } else {
            $content->url = Input::get('url');
        }

        $content->user()->associate(Auth::user());
        $content->group()->associate($group);

        $content->save();

        // Download thumbnail in background to don't waste user time
        $thumbnail = $request->get('thumbnail');

        if ($thumbnail != 'false' && $thumbnail != 'off') {
            $content->thumbnail_loading = true;
            Queue::push('Strimoid\Handlers\DownloadThumbnail', [
                'id' => $content->getKey(),
            ]);
        }

        return Response::json([
            'status' => 'ok', '_id' => $content->getKey(), 'content' => $content,
        ]);
    }

    /**
     * @param Request $request
     * @param Content $content
     *
     * @return mixed
     */
    public function edit(Request $request, $content)
    {
        if (! $content->canEdit(Auth::user())) {
            return Response::json([
                'status' => 'error', 'error' => 'Minął czas dozwolony na edycję treści.',
            ], 400);
        }

        $rules = [
            'title'       => 'min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
        ];

        if ($content->text) {
            $rules['text'] = 'min:1|max:50000';
        } else {
            $rules['url'] = 'url_custom|max:2048';
        }

        $this->validate($request, $rules);

        $fields = ['title', 'description', 'nsfw', 'eng'];
        $fields[] = ($content->text) ? 'text' : 'url';

        $content->update(Input::only($fields));
    }
}
