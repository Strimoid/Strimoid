<?php

use Summon\Summon;

class ContentController extends BaseController {

    public function showContents($groupName = 'all')
    {
        $groupName = shadow($groupName);
        $routeName = Route::current()->getName();

        $tab = str_contains($routeName, 'new') ? 'new' : 'popular';

        // Show popular instead of all as homepage for guests
        if (Auth::guest() && !Route::input('group')) {
            $groupName = 'popular';
        }

        // Set proper group name if user is having subscribed set as his homepage
        if (Auth::check() && !Route::input('group') && $groupName == 'all'
            && @Auth::user()->settings['homepage_subscribed'])
        {
            $groupName = 'subscribed';
        }

        if (Auth::guest() && in_array($groupName, ['subscribed', 'moderated', 'observed']))
        {
            return Redirect::route('login_form')->with('info_msg', 'Wybrana funkcja dostępna jest wyłącznie dla zalogowanych użytkowników.');
        }

        if (Route::input('folder'))
        {
            $user = Route::input('user') ? User::findOrFail(Route::input('user')) : Auth::user();
            $folder = Folder::findUserFolderOrFail($user->_id, Route::input('folder'));

            if (!$folder->public && (Auth::guest() || $user->_id != Auth::user()->_id))
            {
                App::abort(404);
            }

            $builder = $folder->contents();
            $results['folder'] = $folder;
        }
        elseif (class_exists('Groups\\'. studly_case($groupName)))
        {
            $class = 'Groups\\'. studly_case($groupName);
            $fakeGroup = new $class;
            $builder = $fakeGroup->contents();

            $builder->orderBy('sticky_global', 'desc');

            $results['fakeGroup'] = $fakeGroup;
        }
        else
        {
            $group = Group::where('shadow_urlname', $groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->contents();

            // Allow group moderators to stick contents
            $builder->orderBy('sticky_group', 'desc');

            $results['group'] = $group;
        }

        $builder->with(['user' => function($q) { $q->remember(10); }]);

        // Sort using default field for selected tab, if sort field doesn't contain valid sortable field
        if (in_array(Input::get('sort'), ['comments', 'uv', 'created_at', 'frontpage_at']))
            $builder->orderBy(Input::get('sort'), 'desc');
        elseif ($groupName == 'all' && $tab == 'popular')
            $builder->orderBy('frontpage_at', 'desc');
        else
            $builder->orderBy('created_at', 'desc');

        // Show only contents from selected tab if all param doesn't exist
        if(Input::get('all') === '')
            ;
        elseif(Input::get('test') === '' && isset($group))
            $builder->where('uv', '>', $group->popular_threshold);
        elseif ((!Route::input('group') || $groupName == 'all') && $tab == 'popular')
            $builder->frontpage(true);
        elseif ($groupName == 'all')
            $builder->frontpage(false);
        elseif ($tab == 'popular')
            $builder->where('uv', '>', 2);

        // Time filter
        if (Input::get('time'))
        {
            $builder->where('created_at', '>', new MongoDate(time() - intval(Input::get('time')) * 86400));
        }

        // Show removed contents
        if (Route::current()->getName() == 'group_contents_deleted' && isset($group))
        {
            $builder->onlyTrashed();
        }

        // Paginate
        $contents = $this->cachedPaginate($builder, Settings::get('contents_per_page'), 10);

        // Add sort and filter parameters to paging urls
        $contents->appends([
            'sort' => Input::get('sort'), 'time' => Input::get('time'), 'all' => Input::get('all')
        ]);

        $results['contents'] = $contents;
        $results['group_name'] = $groupName;

        // Return RSS feed for some of routes
        if (ends_with(Route::current()->getName(), '_rss'))
        {
            $feed = Rss::feed('2.0', 'UTF-8');

            $feed->channel(array(
                'title' => 'Strimoid: strona główna',
                'description' => 'Ostatnio popularne treści na portalu Strimoid.pl',
                'link' => 'http://www.strimoid.pl/'
            ));

            foreach ($contents as $content){
                $feed->item(array(
                    'title' => $content->title,
                    'description|cdata' => $content->description,
                    'link' => route('content_comments', $content->_id),
                    'pubdate' => $content->created_at->format(DateTime::RSS)
                ));
            }

            return Response::make($feed, 200, ['Content-Type' => 'text/xml'])->setTtl(60);
        }

        return View::make('content.display', $results);
    }

    public function showComments(Content $content)
    {
        $content->related = $content->getRelated();

        $content->comments_count = $content->comments;
        $content->comments = $content->getComments();

        if (Input::get('sort') == 'uv')
        {
            $content->comments = $content->comments->sortBy(function($comment) {
                return $comment->uv;
            })->reverse();
        }
        elseif (Input::get('sort') == 'replies')
        {
            $content->comments = $content->comments->sortBy(function($comment) {
                return $comment->replies->count();
            })->reverse();
        }

        $results['blockedUsers'] = array();

        if (Auth::check())
        {
            $results['blockedUsers'] = (array) Auth::user()->blockedUsers();
        }

        $results['content'] = $content;
        $results['group'] = $content->group;

        /*
        $results['another_contents'] = Content::where('url', $content->url)
            ->get(['title', 'group_id', 'user_id', 'uv', 'dv']);
        */

        return View::make('content.comments', $results);
    }

    public function showFrame(Content $content)
    {
        return View::make('content.frame', compact('content'));
    }

    public function showAddForm()
    {
        return View::make('content.add');
    }

    public function showEditForm(Content $content)
    {
        if (!$content->canEdit(Auth::user()))
        {
            return Redirect::route('content_comments', $content->_id)
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        return View::make('content.edit', compact('content'));
    }

    public function addContent()
    {
        $rules = [
            'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
            'groupname' => 'required|exists_ci:groups,urlname'
        ];

        if (Input::get('type') == 'link')
            $rules['url'] = 'required|url_custom|max:2048';
        else
            $rules['text'] = 'required|min:1|max:50000';

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::action('ContentController@showAddForm')->withInput()->withErrors($validator);
        }

        $group = Group::where('shadow_urlname', shadow(Input::get('groupname')))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group))
        {
            return Redirect::action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Zostałeś zbanowany w wybranej grupie');
        }

        if ($group->type == Group::TYPE_ANNOUNCEMENTS && !Auth::user()->isModerator($group))
        {
            return Redirect::action('ContentController@showAddForm')
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać treści do wybranej grupy');
        }

        $content = new Content(Input::only([
            'title', 'description', 'nsfw', 'eng'
        ]));

        if (Input::get('type') == 'link')
        {
            $content->url = Input::get('url');
        }
        else
        {
            $content->text = Input::get('text');
        }

        $content->user()->associate(Auth::user());
        $content->group()->associate($group);


        // Download thumbnail in background to don't waste user time
        if (Input::get('thumbnail') == 'on')
        {
            $content->thumbnail_loading = true;
            Queue::push('DownloadThumbnail', ['id' => $content->_id]);
        }

        $content->save();

        return Redirect::route('content_comments', $content->_id);
    }

    public function editContent(Content $content)
    {
        if (!$content->canEdit(Auth::user()))
        {
            return Redirect::route('content_comments', $content->_id)
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        $rules = [
            'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
            'description' => 'max:255',
        ];

        if ($content->text)
        {
            $rules['text'] = 'required|min:1|max:50000';
        }
        else
        {
            $rules['url'] = 'required|url_custom|max:2048';
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::action('ContentController@showEditForm', $content->_id)
                ->withInput()
                ->withErrors($validator);
        }

        $content->title = Input::get('title');
        $content->description = Input::get('description');

        if ($content->text)
        {
            $content->text = Input::get('text');
        }
        else
        {
            $content->url = Input::get('url');
        }

        $content->nsfw = Input::get('nsfw');
        $content->eng = Input::get('eng');

        $content->save();

        return Redirect::route('content_comments', $content->_id);
    }

    public function removeContent(Content $content = null)
    {
        $content = ($content) ?: Content::findOrFail(Input::get('id'));

        if (Carbon::instance($content->created_at)->diffInMinutes() > 60)
        {
            return Response::json(['status' => 'error', 'error' => 'Minął dozwolony czas na usunięcie treści.']);
        }

        if (Auth::user()->_id == $content->user_id)
        {
            $content->forceDelete();
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error', 'error' => 'Nie masz uprawnień do usunięcia tej treści.']);
    }

    public function softRemoveContent()
    {
        $content = Content::findOrFail(Input::get('id'));

        if ($content->canRemove(Auth::user()))
        {
            $content->deleted_by()->associate(Auth::user());
            $content->save();

            $content->delete();

            return Response::json(array('status' => 'ok'));
        }

        return Response::json(array('status' => 'error'));
    }

    public function getEmbedCode(Content $content)
    {
        $embedCode = $content->getEmbed()->getHtml();

        return Response::json(array('status' => 'ok', 'code' => $embedCode));
    }

    public function chooseThumbnail(Content $content)
    {
        if (!$content->canEdit(Auth::user()))
        {
            return Redirect::route('content_comments', $content->_id)
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        $thumbnails = array();

        try {
            $summon = new Summon($content->getURL());

            $thumbnails = $summon->fetch();
        } catch(Exception $e) {}

        $thumbnails['thumbnails'][] = 'http://img.bitpixels.com/getthumbnail?code=74491&size=100&url='. urlencode($content->url);

        Session::put('thumbnails', $thumbnails['thumbnails']);

        return View::make('content.thumbnails', compact('content', 'thumbnails'));
    }

    public function saveThumbnail()
    {
        $content = Content::findOrFail(Input::get('id'));
        $thumbnails = Session::get('thumbnails');

        if (!$content->canEdit(Auth::user()))
        {
            return Redirect::route('content_comments', $content->_id)
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        if (strlen(Input::get('thumbnail')) && $thumbnails[intval(Input::get('thumbnail'))])
            $content->setThumbnail($thumbnails[intval(Input::get('thumbnail'))]);
        else
            $content->removeThumbnail();

        return Redirect::route('content_comments', $content->_id);
    }

    /* === API === */

    public function getIndex()
    {
        $folderName = Input::get('folder');
        $groupName = Input::has('group') ? shadow(Input::get('group')) : 'all';
        $type = Input::has('type') ? Input::get('type') : 'all';

        if (Auth::guest() && in_array($groupName, ['subscribed', 'moderated', 'observed', 'saved']))
        {
            App::abort(403, 'Group available only for logged in users');
        }

        if (Input::has('folder') && !class_exists('Groups\\'. studly_case($folderName)))
        {
            $user = Input::has('user') ? User::findOrFail(Input::get('user')) : Auth::user();
            $folder = Folder::findUserFolderOrFail($user->_id, Input::get('folder'));

            if (!$folder->public && (Auth::guest() || $user->_id != Auth::user()->_id))
            {
                App::abort(404);
            }

            $builder = $folder->contents();
        }
        elseif (class_exists('Groups\\'. studly_case($groupName)))
        {
            $class = 'Groups\\'. studly_case($groupName);
            $fakeGroup = new $class;
            $builder = $fakeGroup->contents();

            $builder->orderBy('sticky_global', 'desc');
        }
        elseif (class_exists('Groups\\'. studly_case($folderName)))
        {
            $class = 'Groups\\'. studly_case($folderName);
            $fakeGroup = new $class;
            $builder = $fakeGroup->contents();

            $builder->orderBy('sticky_global', 'desc');
        }
        else
        {
            $group = Group::where('shadow_urlname', $groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->contents();

            // Allow group moderators to stick contents
            $builder->orderBy('sticky_group', 'desc');
        }

        $builder->with([
            'user' => function($q) { $q->remember(10); },
            'group' => function($q) { $q->remember(10); }
        ]);

        // Sort using default field for selected tab, if sort field doesn't contain valid sortable field
        if (in_array(Input::get('sort'), ['comments', 'score', 'uv', 'created_at', 'frontpage_at']))
            $builder->orderBy(Input::get('sort'), 'desc');
        elseif ($groupName == 'all' && $type == 'popular')
            $builder->orderBy('frontpage_at', 'desc');
        else
            $builder->orderBy('created_at', 'desc');

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
        if (Input::get('time'))
        {
            $builder->where('created_at', '>', new MongoDate(time() - intval(Input::get('time')) * 86400));
        }

        // Domain filter
        if (Input::has('domain'))
        {
            $builder->where('domain', Input::get('domain'));
        }

        // User filter
        if (Input::has('user'))
        {
            $user = User::shadow(Input::get('user'))->firstOrFail();
            $builder->where('user_id', $user->_id);
        }

        $perPage = 20;

        if (Input::has('per_page') && Input::get('per_page') > 0 &&  Input::get('per_page') <= 100)
        {
            $perPage = Input::get('per_page');
        }

        return $builder->paginate($perPage);
    }

    public function show(Content $content)
    {
        $content->load([
            'related', 'comments', 'comments.user', 'comments.replies', 'group', 'user'
        ]);

        return $content;
    }

    public function store()
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

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Response::json([
                'status' => 'error', 'error' => $validator->messages()->first()
            ], 400);
        }

        $group = Group::shadow(Input::get('group'))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group))
        {
            return Response::json([
                'status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.'
            ], 400);
        }

        if ($group->type == Group::TYPE_ANNOUNCEMENTS && !Auth::user()->isModerator($group))
        {
            return Response::json([
                'status' => 'error', 'error' => 'Użytkownik nie może dodawać treści w tej grupie.'
            ], 400);
        }

        $content = new Content();
        $content->title = Input::get('title');
        $content->description = Input::get('description');
        $content->nsfw = Input::get('nsfw');
        $content->eng = Input::get('eng');

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

        return Response::json(['status' => 'ok', '_id' => $content->_id, 'content' => $content]);
    }

    public function edit(Content $content)
    {
        if (!$content->canEdit(Auth::user()))
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

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Response::json([
                'status' => 'error', 'error' => $validator->messages()->first()
            ], 400);
        }

        $fields = ['title', 'description', 'nsfw', 'eng'];

        if ($content->text)
        {
            $fields[] = 'text';
        }
        else
        {
            $fields[] = 'url';
        }

        $content->update(Input::only($fields));
    }

}
