<?php namespace Strimoid\Http\Controllers\Api; 

use Auth, Input;
use Illuminate\Http\Request;
use Strimoid\Models\Content;

class ContentController extends BaseController {

    /**
     * @return mixed
     */
    public function index()
    {
        $folderName = Input::get('folder');
        $groupName = Input::has('group') ? shadow(Input::get('group')) : 'all';
        $type = Input::has('type') ? Input::get('type') : 'all';

        $className = 'Strimoid\\Models\\Folders\\'. studly_case($folderName ?: $groupName);

        if (Auth::guest() && in_array($groupName, ['subscribed', 'moderated', 'observed', 'saved']))
        {
            App::abort(403, 'Group available only for logged in users');
        }

        if (Input::has('folder') && !class_exists('Folders\\'. studly_case($folderName)))
        {
            $user = Input::has('user') ? User::findOrFail(Input::get('user')) : Auth::user();
            $folder = Folder::findUserFolderOrFail($user->getKey(), Input::get('folder'));

            if (!$folder->public && (Auth::guest() || $user->getKey() !== Auth::id()))
            {
                App::abort(404);
            }

            $builder = $folder->contents();
        }
        elseif (class_exists($className))
        {
            $fakeGroup = new $className;
            $builder = $fakeGroup->contents();
        }
        else
        {
            $group = Group::shadow($groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->contents();
        }

        $builder->with('group', 'user');

        // Sort using default field for selected tab, if sort field doesn't contain valid sortable field
        if (in_array(Input::get('sort'), ['comments', 'score', 'uv', 'created_at', 'frontpage_at']))
        {
            $builder->orderBy(Input::get('sort'), 'desc');
        }
        elseif ($groupName == 'all' && $type == 'popular')
        {
            $builder->orderBy('frontpage_at', 'desc');
        }
        else
        {
            $builder->orderBy('created_at', 'desc');
        }

        // Show only contents from selected tab if all param doesn't exist
        if ($type == 'all')
            ;
        elseif ($groupName == 'all' && $type == 'popular')
            $builder->frontpage(true);
        elseif ($groupName == 'all' && $type == 'new')
            $builder->frontpage(false);
        elseif ($type == 'popular')
            $builder->where('uv', '>', 2);

        // Time filter
        $time = Input::get('time');
        if ($time) $builder->fromDaysAgo(Input::get('time'));

        // Domain filter
        $domain = Input::get('domain');
        if ($domain) $builder->where('domain', Input::get('domain'));

        // User filter
        if (Input::has('user'))
        {
            $user = User::shadow(Input::get('user'))->firstOrFail();
            $builder->where('user_id', $user->getKey());
        }

        $perPage = Input::has('per_page')
            ? between(Input::get('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    /**
     * @param Content $content
     * @return Content
     */
    public function show(Content $content)
    {
        $content->load([
            'related', 'comments', 'comments.user', 'comments.replies', 'group', 'user'
        ]);

        return $content;
    }

    /**
     * @return mixed
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'group' => 'required|exists_ci:groups,urlname'
        ];

        if (Input::get('text'))
        {
            $rules['text'] = 'required|min:1|max:50000';
        }
        else
        {
            $rules['url'] = 'required|url_custom';
        }

        $this->validate($request, $rules);

        $group = Group::shadow(Input::get('group'))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group))
        {
            return Response::json([
                'status' => 'error',
                'error' => 'Użytkownik został zbanowany w wybranej grupie.'
            ], 400);
        }

        if ($group->type == Group::TYPE_ANNOUNCEMENTS && !Auth::user()->isModerator($group))
        {
            return Response::json([
                'status' => 'error',
                'error' => 'Użytkownik nie może dodawać treści w tej grupie.'
            ], 400);
        }

        $attributes = Input::only(['title', 'description', 'nsfw', 'eng']);
        $content = new Content($attributes);

        if (Input::get('text'))
        {
            $content->text = Input::get('text');
        }
        else
        {
            $content->url = Input::get('url');

            if (Input::get('thumbnail') != 'false' && Input::get('thumbnail') != 'off')
            {
                $content->autoThumbnail();
            }
        }

        $content->user()->associate(Auth::user());
        $content->group()->associate($group);
        $content->save();

        return Response::json([
            'status' => 'ok', '_id' => $content->getKey(), 'content' => $content
        ]);
    }

    /**
     * @param Request $request
     * @param Content $content
     * @return mixed
     */
    public function edit(Request $request, $content)
    {
        if ( ! $content->canEdit(Auth::user()))
        {
            return Response::json([
                'status' => 'error', 'error' => 'Minął czas dozwolony na edycję treści.'
            ], 400);
        }

        $rules = [
            'title' => 'min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
        ];

        if ($content->text)
        {
            $rules['text'] = 'min:1|max:50000';
        }
        else
        {
            $rules['url'] = 'url_custom|max:2048';
        }

        $this->validate($request, $rules);

        $fields = ['title', 'description', 'nsfw', 'eng'];
        $fields[] = ($content->text) ? 'text' : 'url';

        $content->update(Input::only($fields));
    }

}
