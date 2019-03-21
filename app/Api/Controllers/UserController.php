<?php

namespace Strimoid\Api\Controllers;

use Strimoid\Models\GroupModerator;
use Strimoid\Models\User;

class UserController
{
    public function show($username)
    {
        $user = User::name($username)->firstOrFail();

        return $this->getInfo($user);
    }

    public function showCurrentUser()
    {
        $user = user();

        $info = $this->getInfo($user);

        $info['subscribed_groups'] = user()->subscribedGroups();
        $info['blocked_groups'] = user()->blockedGroups();
        $info['moderated_groups'] = user()->moderatedGroups();

        $info['folders'] = user()->folders->toArray();

        return $info;
    }

    public function getInfo($user)
    {
        $stats = [
            'contents' => (int) $user->contents->count(),
            'comments' => (int) $user->comments->count(),
            'entries' => (int) $user->entries->count(),
            'moderated_groups' => intval(GroupModerator::where('user_id', $user->getKey())->count()),
        ];

        return [
            'name' => $user->name,
            'age' => $user->age,
            'avatar' => $user->avatar,
            'description' => $user->description,
            'joined' => current($user->created_at),
            'location' => $user->location,
            'sex' => $user->sex,
            'stats' => $stats,
        ];
    }
}
