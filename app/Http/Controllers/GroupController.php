<?php namespace Strimoid\Http\Controllers;

class GroupController extends BaseController {

    public function showList()
    {
        $builder = Group::where('type', '!=', Group::TYPE_PRIVATE);

        if (Input::get('sort') == 'newest')
        {
            $builder->orderBy('created_at', 'desc');
        }
        else
        {
            $builder->orderBy('subscribers', 'desc');
        }

        $data['groups'] = $builder->paginate(20);

        if (Auth::check())
        {
            $ids = (array) Auth::user()->data->recommended_groups;
            $data['recommendedGroups'] = Group::whereIn('_id', $ids)->get();
        }

        return View::make('group.list', $data);
    }

    public function showJSONList()
    {
        $groups = array();

        foreach(Group::where('type', '!=', Group::TYPE_PRIVATE)->get() as $group)
        {
            $groups[] = [
                'value' => $group->urlname,
                'name' => $group->name,
                'avatar' => $group->getAvatarPath(),
                'contents' => intval(Content::where('group_id', '=', $group->_id)->count())
            ];
        }

        return Response::json($groups);
    }

    public function showSubscribed()
    {
        $subscriptions = GroupSubscriber::where('user_id', Auth::user()->getKey())->with('group')->get();

        $names = array();

        foreach($subscriptions as $subscription)
        {
            $names[] = $subscription->group->urlname;
        }

        return Response::json(['status' => 'ok', 'groups' => $names]);
    }

    public function showWizard()
    {
        $builder = Group::where('type', '!=', Group::TYPE_PRIVATE);

        if (Input::get('sort') == 'popularity')
        {
            $builder->orderBy('subscribers', 'desc');
        }
        else
        {
            $builder->orderBy('created_at', 'desc');
        }

        $groups = $builder->paginate(10);

        if (Auth::check()) {
            foreach ($groups as $group)
            {
                $subscribed = GroupSubscriber::where('group_id', $group->getKey())
                    ->where('user_id', Auth::user()->getKey())
                    ->first();

                if ($subscribed)
                {
                    $group->subscribed = true;
                }
            }
        }

        return View::make('group.wizard', array('groups' => $groups));
    }

    public function showCreateForm()
    {
        return View::make('group.create');
    }

    public function showSettings($groupName)
    {
        $group = Group::where('urlname', $groupName)->firstOrFail();

        if (!Auth::user()->isAdmin($group))
        {
            App::abort(403, 'Access denied');
        }

        $filename = Config::get('app.uploads_path').'/styles/'. ($group->style ? $group->style : Str::lower($group->urlname) .'.css');

        $data['group'] = $group;
        $data['css'] = '';
        $data['moderators'] = GroupModerator::with('user')->where('group_id', $group->getKey())->get();
        $data['bans'] = GroupBanned::with('user')->where('group_id', $group->getKey())->get();

        if (file_exists($filename))
        {
            $data['css'] = File::get($filename);
        }

        return View::make('group.settings', $data);
    }

    public function saveProfile($groupName)
    {
        $group = Group::where('urlname', $groupName)->firstOrFail();

        if (!Auth::user()->isAdmin($group))
        {
            App::abort(403, 'Access denied');
        }

        $rules = [
            'avatar' => 'image|max:250',
            'name' => 'required|min:3|max:50',
            'description' => 'max:255',
            'sidebar' => 'max:5000',
            'tags' => 'max:1000|regex:/^[a-z0-9,\pL ]+$/u',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::action('GroupController@showSettings', $groupName)
                ->withInput()->withErrors($validator);
        }

        $group->name = Input::get('name');
        $group->description = Input::get('description');

        $group->sidebar = Input::get('sidebar');

        if (Input::hasFile('avatar'))
        {
            $group->setAvatar(Input::file('avatar')->getRealPath());
        }

        if (Input::get('nsfw') == 'on')
        {
            $group->nsfw = true;
        }
        elseif ($group->nsfw)
        {
            $group->unset('nsfw');
        }

        $tags = Str::lower(Input::get('tags'));
        $tags = explode(',', $tags);
        $tags = array_map('trim', $tags);

        if (count($tags))
        {
            $group->tags = $tags;
        }

        $group->save();

        return Redirect::action('GroupController@showSettings', $groupName)->with('success_msg',
            'Zmiany zostały zapisane.');
    }

    public function saveSettings($groupName)
    {
        $group = Group::where('urlname', $groupName)->firstOrFail();

        if (!Auth::user()->isAdmin($group))
        {
            App::abort(403, 'Access denied');
        }

        $validator = Validator::make(Input::all(), [
            'labels' => 'max:1000|regex:/^[a-z0-9,\pL ]+$/u',
        ]);

        if ($validator->fails())
        {
            return Redirect::action('GroupController@showSettings', $groupName)->withInput()->withErrors($validator);
        }

        $settings['enable_labels'] = Input::get('enable_labels') == 'on' ? true : false;

        $group->settings = $settings;

        $labels = explode(',', Input::get('labels'));
        $labels = array_map('trim', $labels);

        $group->labels = $labels;

        $group->save();

        return Redirect::action('GroupController@showSettings', $groupName)->with('success_msg',
            'Zmiany zostały zapisane.');
    }

    public function saveStyle($groupName)
    {
        $group = Group::where('urlname', $groupName)->firstOrFail();

        if (!Auth::user()->isAdmin($group))
        {
            App::abort(403, 'Access denied');
        }

        $validator = Validator::make(Input::all(), ['css' => 'max:15000']);

        if ($validator->fails())
        {
            return Redirect::action('GroupController@showSettings', $groupName)->withInput()->withErrors($validator);
        }

        $group->setStyle(Input::get('css'));
        $group->save();

        return Redirect::action('GroupController@showSettings', $groupName)->with('success_msg',
            'Zmiany zostały zapisane.');
    }

    public function showModeratorList($groupName)
    {
        $group = Group::where('shadow_urlname', shadow($groupName))->firstOrFail();
        $moderators = GroupModerator::where('group_id', $group->getKey())
            ->orderBy('created_at', 'asc')
            ->with('user')
            ->paginate(25);

        return View::make('group.moderators', compact('group', 'moderators'));
    }

    public function addModerator()
    {
        $group = Group::shadow(Input::get('groupname'))->firstOrFail();
        $user = User::shadow(Input::get('username'))->firstOrFail();

        if (!Auth::user()->isAdmin($group))
        {
            App::abort(403, 'Access denied');
        }

        if ($user->isModerator($group))
        {
            return Redirect::route('group_moderators', $group->urlname);
        }

        if ($user->isBlocking($group))
        {
            return Redirect::route('group_moderators', $group->urlname)
                ->with('danger_msg', 'Nie możesz dodać wybranego użytkownika jako moderatora, ponieważ zablokował tą grupę.');
        }

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate($user);

        if (Input::get('admin') == 'on')
        {
            $moderator->type = 'admin';
        }
        else
        {
            $moderator->type = 'moderator';
        }

        $moderator->save();

        // Send notification to new moderator
        $this->sendNotifications([$user->_id], function($notification) use($moderator, $group) {
            $notification->type = 'moderator';

            $positionTitle = $moderator->type == 'admin' ? 'administratorem' : 'moderatorem';
            $notification->setTitle('Zostałeś '. $positionTitle .' w grupie '. $group->urlname);

            $notification->group()->associate($group);
            $notification->save();
        });

        // Log this action
        $action = new ModeratorAction();
        $action->type = ModeratorAction::TYPE_MODERATOR_ADDED;
        $action->is_admin = $moderator->type == 'admin' ? true : false;
        $action->moderator()->associate(Auth::user());
        $action->target()->associate($user);
        $action->group()->associate($group);
        $action->save();

        Cache::forget($user->_id . '.moderated_groups');

        return Redirect::route('group_moderators', $group->urlname);
    }

    public function removeModerator()
    {
        $moderator = GroupModerator::findOrFail(Input::get('id'));
        $group = $moderator->group;

        if (!Auth::user()->isAdmin($moderator->group))
        {
            App::abort(403, 'Access denied');
        }

        if ($moderator->user_id == $group->creator_id && Auth::id() != $group->creator_id)
        {
            return Response::json(['status' => 'error']);
        }

        $moderator->delete();

        // Log this action
        $action = new ModeratorAction();
        $action->type = ModeratorAction::TYPE_MODERATOR_REMOVED;
        $action->is_admin = $moderator->type == 'admin' ? true : false;
        $action->moderator()->associate(Auth::user());
        $action->target()->associate($moderator);
        $action->group()->associate($group);
        $action->save();

        Cache::forget($moderator->user_id . '.moderated_groups');

        return Response::json(['status' => 'ok']);
    }

    public function showBannedList($groupName)
    {
        $group = Group::shadow($groupName)->firstOrFail();
        $bans = GroupBanned::where('group_id', $group->getKey())
            ->orderBy('created_at', 'desc')->with('user')->paginate(25);

        return View::make('group.bans', array('group' => $group, 'bans' => $bans));
    }

    public function addBan()
    {
        $user = User::shadow(Input::get('username'))->firstOrFail();
        $group = Group::shadow(Input::get('groupname'))->firstOrFail();

        $validator = Validator::make(Input::all(), ['reason' => 'max:255']);

        if ($validator->fails())
        {
            return Redirect::route('group_banned', $group->urlname)->withInput()->withErrors($validator);
        }

        if (Input::get('everywhere') == 'on')
        {
            $moderated = GroupModerator::with('group')->where('user_id', Auth::user()->_id)->get();

            foreach ($moderated as $mod)
            {
                $mod->group->banUser($user, Input::get('reason'));
            }
        }
        else
        {
            if (!Auth::user()->isModerator($group))
            {
                App::abort(403, 'Access denied');
            }

            $group->banUser($user, Input::get('reason'));
        }

        return Redirect::route('group_banned', $group->urlname);
    }

    public function removeBan()
    {
        $ban = GroupBanned::findOrFail(Input::get('id'));

        if (!Auth::user()->isModerator($ban->group))
        {
            App::abort(403, 'Access denied');
        }

        $ban->delete();

        return Response::json(['status' => 'ok']);
    }

    public function createGroup()
    {
        $rules = array(
            'urlname' => 'required|min:3|max:32|unique_ci:groups|reserved_groupnames|regex:/^[a-zA-Z0-9_żźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/i',
            'groupname' => 'required|min:3|max:50',
            'desc' => 'max:255'
        );

        // Require 15 minutes break before creating next group
        $group = Group::where('creator_id', Auth::user()->getKey())->orderBy('created_at', 'desc')->first();

        if ($group && Carbon::instance($group->created_at)->diffInMinutes() < 30)
        {
            Input::flash();

            $diff = 30 - Carbon::instance($group->created_at)->diffInMinutes();
            $minutes = Lang::choice('pluralization.minutes', $diff);

           return Redirect::action('GroupController@showCreateForm')
                ->with('danger_msg', 'Kolejną grupę będziesz mógł utworzyć za '. $minutes);
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            Input::flash();
            return Redirect::action('GroupController@showCreateForm')->withErrors($validator);
        }

        $group = new Group();
        $group->_id = Input::get('urlname');
        $group->urlname = Input::get('urlname');
        $group->name = Input::get('groupname');
        $group->description = Input::get('description');

        /*
        if(Input::get('type') == 'moderated')
            $group->type = Group::TYPE_MODERATED;
        elseif(Input::get('type') == 'private')
            $group->type = Group::TYPE_PRIVATE;
        */

        $group->creator()->associate(Auth::user());
        $group->save();

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate(Auth::user());
        $moderator->type = 'admin';
        $moderator->save();

        Cache::forget('user.'. Auth::id() . '.moderated_groups');

        return Redirect::route('group_contents', $group->urlname)
            ->with('success_msg', 'Nowa grupa o nazwie '. $group->name .' została utworzona.');
    }

    public function subscribeGroup()
    {
        $group = Group::shadow(Input::get('name'))->firstOrFail();

        $group->checkAccess();

        if (Auth::user()->isSubscriber($group))
        {
            return Response::make('Already subscribed', 400);
        }

        $subscriber = new GroupSubscriber();
        $subscriber->user()->associate(Auth::user());
        $subscriber->group()->associate($group);
        $subscriber->save();

        $group->increment('subscribers');

        Cache::forget('user.'. Auth::id() . '.subscribed_groups');

        return Response::json(['status' => 'ok']);
    }

    public function unsubscribeGroup()
    {
        $group = Group::shadow(Input::get('name'))->firstOrFail();

        $subscriber = GroupSubscriber::where('group_id', $group->_id)->where('user_id', Auth::id())->first();

        if (!$subscriber)
        {
            return Response::make('Not subscribed', 400);
        }

        $subscriber->delete();

        $group->decrement('subscribers');

        Cache::forget('user.'. Auth::id() .'.subscribed_groups');

        return Response::json(['status' => 'ok']);
    }

    public function blockGroup()
    {
        $group = Group::where('urlname', Input::get('name'))->firstOrFail();
        $user = Auth::user();

        $group->checkAccess();

        if (GroupBlock::where('group_id', $group->getKey())->where('user_id', $user->getKey())->first())
        {
            return Response::make('Already blocked', 400);
        }

        $block = new GroupBlock();
        $block->user()->associate($user);
        $block->group()->associate($group);
        $block->save();

        Cache::forget('user.'. Auth::id() . '.blocked_groups');

        return Response::json(['status' => 'ok']);
    }

    public function unblockGroup()
    {
        $group = Group::where('urlname', Input::get('name'))->firstOrFail();
        $user = Auth::user();

        $block = GroupBlock::where('group_id', $group->getKey())->where('user_id', $user->getKey())->first();

        if (!$block)
        {
            return Response::make('Not blocked', 400);
        }

        $block->delete();

        Cache::forget('user.'. Auth::user()->_id . '.blocked_groups');

        return Response::json(['status' => 'ok']);
    }

    public function wizard($tag = null)
    {
        $popularTags = ['programowanie', 'muzyka', 'gry', 'obrazki', 'it', 'internet', 'linux', 'humor', 'nauka',
            'strimoid', 'zdjęcia', 'jam', 'oldschool', 'technika', 'śmieszne', 'art', 'technologia', 'wpisy', 'sztuka',
            'zainteresowania'];

        if ($tag)
            $groups = Group::where('tags', $tag)->orderBy('subscribers', 'desc')->paginate(25);
        else
            $groups = Group::orderBy('subscribers', 'desc')->paginate(25);

        return View::make('group.wizard', ['popular_tags' => $popularTags, 'groups' => $groups]);
    }

    public function getSidebar($group)
    {
        $group = Group::shadow($group)->firstOrFail();
        $sidebar = $group->sidebar;

        return Response::json(compact('sidebar'));
    }

    public function show($groupName)
    {
        return $this->getInfo($groupName);
    }

    public function getInfo($groupName)
    {
        $group = Group::shadow($groupName)->with('creator')->firstOrFail();
        $group->checkAccess();

        $stats = [
            'contents' => intval(Content::where('group_id', $group->_id)->count()),
            'comments' => intval(Content::where('group_id', $group->getKey())->sum('comments')),
            'entries' => intval(Entry::where('group_id', $group->_id)->count()),
            'banned' => intval(GroupBanned::where('group_id', $group->getKey())->count()),
            'subscribers' => $group->subscribers,
            'moderators' => intval(GroupModerator::where('group_id', $group->getKey())->count()),
        ];

        return array_merge(
            $group->toArray(),
            ['stats' => $stats]
        );
    }

    public function index()
    {
        $builder = Group::where('type', '!=', Group::TYPE_PRIVATE);

        if (Input::has('name'))
        {
            $builder->where('name', 'like', '%'. Input::get('name') .'%');
        }

        if (in_array(Input::get('sort'), ['created_at', 'subscribers']))
        {
            $builder->orderBy(Input::get('sort'), 'desc');
        }
        else
        {
            $builder->orderBy('created_at', 'desc');
        }

        $groups = $builder->paginate(100);

        return $groups;
    }

}