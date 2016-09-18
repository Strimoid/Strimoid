<?php

namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Lang;
use Redirect;
use Response;
use Storage;
use Str;
use Strimoid\Models\Group;
use Strimoid\Models\GroupBan;
use Strimoid\Models\GroupModerator;

class GroupController extends BaseController
{
    public function showList()
    {
        $builder = Group::with('creator')->where('type', '!=', 'private');

        if (request('sort') == 'newest') {
            $builder->orderBy('created_at', 'desc');
        } else {
            $builder->orderBy('subscribers_count', 'desc');
        }

        $data['groups'] = $builder->paginate(20)->appends(['sort' => request('sort')]);

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
        $names = user()->subscribedGroups()->getQuery()->pluck('urlname');

        return Response::json(['status' => 'ok', 'groups' => $names]);
    }

    public function showWizard()
    {
        $builder = Group::where('type', '!=', 'private');

        $sortBy = request('sort') == 'popularity'
        ? 'subscribers_count' : 'created_at';
        $builder->orderBy($sortBy, 'desc');

        $groups = $builder->paginate(10);

        return view('group.wizard', compact('groups'));
    }

    public function showCreateForm()
    {
        return view('group.create');
    }

    public function showSettings(Group $group)
    {
        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
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
        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        $this->validate($request, [
            'avatar'      => 'image|max:250',
            'name'        => 'required|min:3|max:50',
            'description' => 'max:255',
            'sidebar'     => 'max:5000',
            'tags'        => 'max:1000|regex:/^[a-z0-9,\pL ]+$/u',
        ]);

        $group->name = request('name');
        $group->description = request('description');

        $group->sidebar = request('sidebar');

        if (Input::hasFile('avatar')) {
            $group->setAvatar(Input::file('avatar')->getRealPath());
        }

        if (request('nsfw') == 'on') {
            $group->nsfw = true;
        } elseif ($group->nsfw) {
            $group->nsfw = false;
        }

        $tags = Str::lower(request('tags'));
        $tags = explode(',', $tags);
        $tags = array_map('trim', $tags);

        if (count($tags)) {
            //$group->tags = $tags;
        }

        $group->save();

        return Redirect::action('GroupController@showSettings', $group)
            ->with('success_msg', 'Zmiany zostały zapisane.');
    }

    public function saveSettings(Request $request, $group)
    {
        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        $this->validate($request, [
            'labels' => 'max:1000|regex:/^[a-z0-9,\pL ]+$/u',
        ]);

        $settings['enable_labels'] = request('enable_labels') == 'on' ? true : false;

        $group->settings = $settings;

        $labels = explode(',', request('labels'));
        $labels = array_map('trim', $labels);

        $group->labels = $labels;

        $group->save();

        return Redirect::action('GroupController@showSettings', $group)->with('success_msg',
            'Zmiany zostały zapisane.');
    }

    public function saveStyle(Request $request, $group)
    {
        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        $this->validate($request, ['css' => 'max:15000']);

        $group->setStyle(request('css'));
        $group->save();

        return Redirect::action('GroupController@showSettings', $group)->with('success_msg',
            'Zmiany zostały zapisane.');
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
        $group->urlname = request('urlname');
        $group->name = request('groupname');
        $group->description = request('description');
        $group->creator()->associate(user());
        $group->save();

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate(user());
        $moderator->type = 'admin';
        $moderator->save();

        return Redirect::route('group_contents', $group->urlname)
            ->with('success_msg', 'Nowa grupa o nazwie '.$group->name.' została utworzona.');
    }

    public function subscribeGroup($group)
    {
        $group->checkAccess();

        if (user()->isSubscriber($group)) {
            return Response::make('Already subscribed', 400);
        }

        user()->subscribedGroups()->attach($group);
        \Cache::tags(['user.subscribed-groups', 'u.'.auth()->id()])->flush();

        return Response::json(['status' => 'ok']);
    }

    public function unsubscribeGroup($group)
    {
        user()->subscribedGroups()->detach($group);
        \Cache::tags(['user.subscribed-groups', 'u.'.auth()->id()])->flush();

        return Response::json(['status' => 'ok']);
    }

    public function blockGroup($group)
    {
        $group->checkAccess();

        user()->blockedGroups()->attach($group);
        \Cache::tags(['user.blocked-groups', 'u.'.auth()->id()])->flush();

        return Response::json(['status' => 'ok']);
    }

    public function unblockGroup($group)
    {
        user()->blockedGroups()->detach();
        \Cache::tags(['user.blocked-groups', 'u.'.auth()->id()])->flush();

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
            $groups = Group::orderBy('id', 'desc')->paginate(25);
        }

        return view('group.wizard', ['popular_tags' => $popularTags, 'groups' => $groups]);
    }

    public function getSidebar($group)
    {
        $sidebar = $group->sidebar;

        return Response::json(compact('sidebar'));
    }
}
