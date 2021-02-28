<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Strimoid\Models\Group;
use Strimoid\Models\GroupBan;
use Strimoid\Models\GroupModerator;

class GroupController extends BaseController
{
    public function __construct(private \Illuminate\Contracts\View\Factory $viewFactory, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private \Illuminate\Filesystem\FilesystemManager $filesystemManager, private \Illuminate\Routing\Redirector $redirector, private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Translation\Translator $translator, private \Illuminate\Cache\CacheManager $cacheManager, private \Illuminate\Contracts\Auth\Guard $guard)
    {
    }
    public function showList(\Illuminate\Http\Request $request)
    {
        $builder = Group::with('creator')->where('type', '!=', 'private');

        if ($request->input('sort') === 'newest') {
            $builder->orderBy('created_at', 'desc');
        } else {
            $builder->orderBy('subscribers_count', 'desc');
        }

        $data['groups'] = $builder->paginate(20)->appends(['sort' => $request->input('sort')]);

        return $this->viewFactory->make('group.list', $data);
    }

    public function showJSONList(): JsonResponse
    {
        $results = [];
        $groups = Group::where('type', '!=', 'private')->get();

        foreach ($groups as $group) {
            $results[] = [
                'value' => $group->urlname,
                'name' => $group->name,
                'avatar' => $group->getAvatarPath(20, 20),
                'contents' => (int) $group->contents()->count(),
            ];
        }

        return $this->responseFactory->json($results)
            ->setPublic()
            ->setMaxAge(3600);
    }

    public function showSubscribed()
    {
        $names = user()->subscribedGroups()->getQuery()->pluck('urlname');

        return $this->responseFactory->json(['status' => 'ok', 'groups' => $names]);
    }

    public function showWizard(\Illuminate\Http\Request $request)
    {
        $builder = Group::where('type', '!=', 'private');

        $sortBy = $request->input('sort') === 'popularity'
        ? 'subscribers_count' : 'created_at';
        $builder->orderBy($sortBy, 'desc');

        $groups = $builder->paginate(10);

        return $this->viewFactory->make('group.wizard', compact('groups'));
    }

    public function showCreateForm()
    {
        return $this->viewFactory->make('group.create');
    }

    public function showSettings(Group $group)
    {
        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        $filename = $group->style ?: Str::lower($group->urlname) . '.css';
        $disk = $this->filesystemManager->disk('styles');

        $data['group'] = $group;
        $data['css'] = $disk->exists($filename) ? $disk->get($filename) : '';

        $data['moderators'] = GroupModerator::with('user')
            ->where('group_id', $group->getKey())
            ->get();

        $data['bans'] = GroupBan::with('user')
            ->where('group_id', $group->getKey())
            ->get();

        return $this->viewFactory->make('group.settings', $data);
    }

    public function saveProfile(Request $request, $group)
    {
        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        $this->validate($request, [
            'avatar' => 'image|max:250',
            'name' => 'required|min:3|max:50',
            'description' => 'max:255',
            'sidebar' => 'max:5000',
            'tags' => 'max:1000|regex:/^[a-z0-9,\pL ]+$/u',
        ]);

        $group->name = $request->input('name');
        $group->description = $request->input('description');

        $group->sidebar = $request->input('sidebar');

        if ($request->hasFile('avatar')) {
            $group->setAvatar($request->file('avatar')->getRealPath());
        }

        if ($request->input('nsfw') === 'on') {
            $group->nsfw = true;
        } elseif ($group->nsfw) {
            $group->nsfw = false;
        }

        /*
        $tags = Str::lower(request('tags'));
        $tags = explode(',', $tags);
        $tags = array_map('trim', $tags);


        if (count($tags)) {
            $group->tags = $tags;
        }
        */

        $group->save();

        return $this->redirector->action('GroupController@showSettings', $group)
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

        $settings['enable_labels'] = $request->input('enable_labels') === 'on';

        $group->settings = $settings;

        $labels = explode(',', $request->input('labels'));
        $labels = array_map('trim', $labels);

        $group->labels = $labels;

        $group->save();

        return $this->redirector->action('GroupController@showSettings', $group)->with(
            'success_msg',
            'Zmiany zostały zapisane.'
        );
    }

    public function saveStyle(Request $request, $group)
    {
        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        $this->validate($request, ['css' => 'max:15000']);

        $group->setStyle($request->input('css'));
        $group->save();

        return $this->redirector->action('GroupController@showSettings', $group)->with(
            'success_msg',
            'Zmiany zostały zapisane.'
        );
    }

    public function createGroup(Request $request)
    {
        // Require 15 minutes break before creating next group
        $group = Group::where('creator_id', $this->authManager->id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($group && $group->created_at->diffInMinutes() < 30) {
            $request->flash();

            $diff = 30 - $group->created_at->diffInMinutes();
            $minutes = $this->translator->choice('pluralization.minutes', $diff);

            return $this->redirector->action('GroupController@showCreateForm')
                ->with('danger_msg', 'Kolejną grupę będziesz mógł utworzyć za ' . $minutes);
        }

        $this->validate($request, [
            'urlname' => 'required|min:3|max:32|unique:groups|reserved_groupnames|regex:/^[a-zA-Z0-9_żźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/i',
            'groupname' => 'required|min:3|max:50',
            'desc' => 'max:255',
        ]);

        $group = new Group();
        $group->urlname = $request->input('urlname');
        $group->name = $request->input('groupname');
        $group->description = $request->input('description');
        $group->creator()->associate(user());
        $group->save();

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate(user());
        $moderator->type = 'admin';
        $moderator->save();

        return $this->redirector->route('group_contents', $group->urlname)
            ->with('success_msg', 'Nowa grupa o nazwie ' . $group->name . ' została utworzona.');
    }

    public function subscribeGroup($group)
    {
        $group->checkAccess();

        if (user()->isSubscriber($group)) {
            return $this->responseFactory->make('Already subscribed', 400);
        }

        user()->subscribedGroups()->attach($group);
        $this->cacheManager->tags(['user.subscribed-groups', 'u.' . $this->guard->id()])->flush();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function unsubscribeGroup($group)
    {
        user()->subscribedGroups()->detach($group);
        $this->cacheManager->tags(['user.subscribed-groups', 'u.' . $this->guard->id()])->flush();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function blockGroup($group)
    {
        $group->checkAccess();

        user()->blockedGroups()->attach($group);
        $this->cacheManager->tags(['user.blocked-groups', 'u.' . $this->guard->id()])->flush();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function unblockGroup($group)
    {
        user()->blockedGroups()->detach();
        $this->cacheManager->tags(['user.blocked-groups', 'u.' . $this->guard->id()])->flush();

        return $this->responseFactory->json(['status' => 'ok']);
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

        return $this->viewFactory->make('group.wizard', ['popular_tags' => $popularTags, 'groups' => $groups]);
    }

    public function getSidebar($group)
    {
        $sidebar = $group->sidebar;

        return $this->responseFactory->json(compact('sidebar'));
    }
}
