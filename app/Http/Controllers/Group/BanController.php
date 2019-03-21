<?php

namespace Strimoid\Http\Controllers\Group;

use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Group;
use Strimoid\Models\GroupBan;
use Strimoid\Models\User;

class BanController extends BaseController
{
    public function showBannedList($group)
    {
        $bans = GroupBan::where('group_id', $group->getKey())
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->paginate(25);

        return view('group.bans', compact('group', 'bans'));
    }

    public function addBan()
    {
        $user = User::name(request('username'))->firstOrFail();
        $group = Group::name(request('groupname'))->firstOrFail();

        $this->validate(request(), ['reason' => 'max:255']);

        if (request('everywhere') == '1') {
            foreach (user()->moderatedGroups as $group) {
                $ban = GroupBan::where('group_id', $group->id)->where('user_id', $user->id)->first();
                if (!$ban) {
                    $group->banUser($user, request('reason'));
                }
            }
        } else {
            if (!user()->isModerator($group)) {
                abort(403, 'Access denied');
            }
            $ban = GroupBan::where('group_id', $group->id)->where('user_id', $user->id)->first();
            if (!$ban) {
                $group->banUser($user, request('reason'));
            }
        }

        return redirect()->route('group_banned', $group);
    }

    public function removeBan()
    {
        $ban = GroupBan::findOrFail(request('id'));

        if (!user()->isModerator($ban->group)) {
            abort(403, 'Access denied');
        }

        $ban->delete();

        return response()->json(['status' => 'ok']);
    }
}
