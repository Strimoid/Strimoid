<?php namespace Strimoid\Http\Controllers;

use App;
use Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Input;
use Lang;
use Redirect;
use Response;
use Storage;
use Str;
use Strimoid\Models\Group;
use Strimoid\Models\GroupBan;
use Strimoid\Models\GroupBlock;
use Strimoid\Models\GroupModerator;
use Strimoid\Models\GroupSubscriber;
use Strimoid\Models\ModeratorAction;
use Strimoid\Models\User;

class GroupController extends BaseController
{
    use ValidatesRequests;

    public function showList()
    {
        $builder = Group::with('creator')->where('type', '!=', 'private');

        if (Input::get('sort') == 'newest') {
            $builder->orderBy('created_at', 'desc');
        } else {
            $builder->orderBy('subscribers_count', 'desc');
        }

        $data['groups'] = $builder->paginate(20)->appends(['sort' => Input::get('sort')]);

        return view('group.list', $data);
    }

    public function showJSONList()
    {
        $results = [];
        $groups = Group::where('type', '!=', 'private')->get();

        foreach ($groups as $group) {
            $results[] = [
                'value'    => $group->urlname,
                'name'     => $group->name,
                'avatar'   => $group->getAvatarPath(),
                'contents' => (int) $group->contents()->count(),
            ];
        }

        return Response::json($results);
    }

    public function showSubscribed()
    {
        $names = Auth::user()->subscribedGroups()->getQuery()->lists('urlname');
        return Response::json(['status' => 'ok', 'groups' => $names]);
    }

    public function showWizard()
    {
        $builder = Group::where('type', '!=', 'private');

        $sortBy = Input::get('sort') == 'popularity'
            ? 'subscribers_count' : 'created_at';
        $builder->orderBy($sortBy, 'desc');

        $groups = $builder->paginate(10);

        return view('group.wizard', compact('groups'));
    }

    public function showCreateForm()
    {
        return view('group.create');
    }

    /**
     * @param  Group  $group
     * @return \Illuminate\View\View
     */
    public function showSettings($group)
    {
        if (! Auth::user()->isAdmin($group)) {
            App::abort(403, 'Access denied');
        }

        $filename = $group->style ?: Str::lower($group->urlname).'.css';
        $disk = Storage::disk('styles');

        $data['group'] = $group;
        $data['css'] = $disk->exists($filename) ? $disk->get($filename) : '';

        $data['moderators'] = GroupModerator::with('user')
            ->where('group_id', $group->getKey())
            ->get();

        $data['bans'] = GroupBan::with('user')
            ->where('group_id', $group->getKey())
            ->get();

        return view('group.settings', $data);
    }

    public function saveProfile(Request $request, $group)
    {
        if (! Auth::user()->isAdmin($group)) {
            App::abort(403, 'Access denied');
        }

        $this->validate($request, [
            'avatar'        => 'image|max:250',
            'name'          => 'required|min:3|max:50',
            'description'   => 'max:255',
            'sidebar'       => 'max:5000',
            'tags'          => 'max:1000|regex:/^[a-z0-9,\pL ]+$/u',
        ]);

        $group->name = Input::get('name');
        $group->description = Input::get('description');

        $group->sidebar = Input::get('sidebar');

        if (Input::hasFile('avatar')) {
            $group->setAvatar(Input::file('avatar')->getRealPath());
        }

        if (Input::get('nsfw') == 'on') {
            $group->nsfw = true;
        } elseif ($group->nsfw) {
            $group->nsfw = false;
        }

        $tags = Str::lower(Input::get('tags'));
        $tags = explode(',', $tags);
        $tags = array_map('trim', $tags);

        if (count($tags)) {
            $group->tags = $tags;
        }

        $group->save();

        return Redirect::action('GroupController@showSettings', $group)
            ->with('success_msg', 'Zmiany zostały zapisane.');
    }

    public function saveSettings(Request $request, $group)
    {
        if (! Auth::user()->isAdmin($group)) {
            App::abort(403, 'Access denied');
        }

        $this->validate($request, [
            'labels' => 'max:1000|regex:/^[a-z0-9,\pL ]+$/u',
        ]);

        $settings['enable_labels'] = Input::get('enable_labels') == 'on' ? true : false;

        $group->settings = $settings;

        $labels = explode(',', Input::get('labels'));
        $labels = array_map('trim', $labels);

        $group->labels = $labels;

        $group->save();

        return Redirect::action('GroupController@showSettings', $group)->with('success_msg',
            'Zmiany zostały zapisane.');
    }

    public function saveStyle(Request $request, $group)
    {
        if (! Auth::user()->isAdmin($group)) {
            App::abort(403, 'Access denied');
        }

        $this->validate($request, ['css' => 'max:15000']);

        $group->setStyle(Input::get('css'));
        $group->save();

        return Redirect::action('GroupController@showSettings', $group)->with('success_msg',
            'Zmiany zostały zapisane.');
    }

    public function showModeratorList($group)
    {
        $moderators = GroupModerator::where('group_id', $group->getKey())
            ->orderBy('created_at', 'asc')
            ->with('user')
            ->paginate(25);

        return view('group.moderators', compact('group', 'moderators'));
    }

    public function addModerator()
    {
        $group = Group::name(Input::get('groupname'))->firstOrFail();
        $user = User::name(Input::get('username'))->firstOrFail();

        if (! Auth::user()->isAdmin($group)) {
            App::abort(403, 'Access denied');
        }

        if ($user->isModerator($group)) {
            return Redirect::route('group_moderators', $group->urlname);
        }

        if ($user->isBlocking($group)) {
            return Redirect::route('group_moderators', $group->urlname)
                ->with('danger_msg', 'Nie możesz dodać wybranego użytkownika jako moderatora, ponieważ zablokował tą grupę.');
        }

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate($user);

        $type = Input::get('admin') == 'on' ? 'admin' : 'moderator';
        $moderator->type = $type;

        $moderator->save();

        // Send notification to new moderator
        $this->sendNotifications([$user->getKey()], function ($notification) use ($moderator, $group) {
            $notification->type = 'moderator';

            $positionTitle = $moderator->type == 'admin' ? 'administratorem' : 'moderatorem';
            $notification->setTitle('Zostałeś '.$positionTitle.' w grupie '.$group->urlname);

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

        return Redirect::route('group_moderators', $group->urlname);
    }

    public function removeModerator()
    {
        $moderator = GroupModerator::findOrFail(Input::get('id'));
        $group = $moderator->group;

        if (! Auth::user()->isAdmin($moderator->group)) {
            App::abort(403, 'Access denied');
        }

        if ($moderator->user_id == $group->creator_id
            && Auth::id() != $group->creator_id) {
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

        return Response::json(['status' => 'ok']);
    }

    public function showBannedList($group)
    {
        $bans = GroupBan::where('group_id', $group->getKey())
            ->orderBy('created_at', 'desc')->with('user')->paginate(25);

        return view('group.bans', compact('group', 'bans'));
    }

    public function addBan(Request $request)
    {
        $user = User::name($request->get('username'))->firstOrFail();
        $group = Group::name($request->get('groupname'))->firstOrFail();

        $this->validate($request, ['reason' => 'max:255']);

        if ($request->get('everywhere') == '1') {
            foreach (user()->moderatedGroups as $group) {
                $group->banUser($user, $request->get('reason'));
            }
        } else {
            if (! user()->isModerator($group)) {
                abort(403, 'Access denied');
            }

            $group->banUser($user, $request->get('reason'));
        }

        return redirect()->route('group_banned', $group);
    }

    public function removeBan()
    {
        $ban = GroupBan::findOrFail(Input::get('id'));

        if (! Auth::user()->isModerator($ban->group)) {
            App::abort(403, 'Access denied');
        }

        $ban->delete();

        return Response::json(['status' => 'ok']);
    }

    public function createGroup(Request $request)
    {
        // Require 15 minutes break before creating next group
        $group = Group::where('creator_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($group && $group->created_at->diffInMinutes() < 30) {
            Input::flash();

            $diff = 30 - $group->created_at->diffInMinutes();
            $minutes = Lang::choice('pluralization.minutes', $diff);

            return Redirect::action('GroupController@showCreateForm')
                ->with('danger_msg', 'Kolejną grupę będziesz mógł utworzyć za '.$minutes);
        }

        $this->validate($request, [
            'urlname'   => 'required|min:3|max:32|unique:groups|reserved_groupnames|regex:/^[a-zA-Z0-9_żźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/i',
            'groupname' => 'required|min:3|max:50',
            'desc'      => 'max:255',
        ]);

        $group = new Group();
        $group->urlname = Input::get('urlname');
        $group->name = Input::get('groupname');
        $group->description = Input::get('description');
        $group->creator()->associate(Auth::user());
        $group->save();

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate(Auth::user());
        $moderator->type = 'admin';
        $moderator->save();

        return Redirect::route('group_contents', $group->urlname)
            ->with('success_msg', 'Nowa grupa o nazwie '.$group->name.' została utworzona.');
    }

    public function subscribeGroup($group)
    {
        $group->checkAccess();

        if (Auth::user()->isSubscriber($group)) {
            return Response::make('Already subscribed', 400);
        }

        Auth::user()->subscribedGroups()->attach($group);
        return Response::json(['status' => 'ok']);
    }

    public function unsubscribeGroup($group)
    {
        Auth::user()->subscribedGroups()->detach($group);
        return Response::json(['status' => 'ok']);
    }

    public function blockGroup($group)
    {
        $group->checkAccess();

        if (GroupBlock::where('group_id', $group->getKey())
            ->where('user_id', Auth::id())->first()) {
            return Response::make('Already blocked', 400);
        }

        $block = new GroupBlock();
        $block->user()->associate(Auth::user());
        $block->group()->associate($group);
        $block->save();

        return Response::json(['status' => 'ok']);
    }

    public function unblockGroup($group)
    {
        $block = GroupBlock::where('group_id', $group->getKey())
            ->where('user_id', Auth::id())->first();

        if (! $block) {
            return Response::make('Not blocked', 400);
        }

        $block->delete();

        return Response::json(['status' => 'ok']);
    }

    public function wizard($tag = null)
    {
        $popularTags = ['programowanie', 'muzyka', 'gry', 'obrazki', 'it',
            'internet', 'linux', 'humor', 'nauka', 'strimoid', 'zdjęcia',
            'jam', 'oldschool', 'technika', 'śmieszne', 'art', 'technologia',
            'wpisy', 'sztuka', 'zainteresowania', ];

        if ($tag) {
            $groups = Group::where('tags', $tag)->orderBy('subscribers', 'desc')->paginate(25);
        } else {
            $groups = Group::orderBy('subscribers', 'desc')->paginate(25);
        }

        return view('group.wizard', ['popular_tags' => $popularTags, 'groups' => $groups]);
    }

    public function getSidebar($group)
    {
        $sidebar = $group->sidebar;
        return Response::json(compact('sidebar'));
    }
}
