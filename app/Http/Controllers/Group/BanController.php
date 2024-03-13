<?php

namespace Strimoid\Http\Controllers\Group;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Group;
use Strimoid\Models\GroupBan;
use Strimoid\Models\User;

class BanController extends BaseController
{
    public function __construct(private readonly \Illuminate\Contracts\View\Factory $viewFactory, private readonly \Illuminate\Routing\Redirector $redirector, private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }
    public function showBannedList($group)
    {
        $bans = GroupBan::where('group_id', $group->getKey())
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->paginate(25);

        return $this->viewFactory->make('group.bans', compact('group', 'bans'));
    }

    public function addBan(\Illuminate\Http\Request $request): RedirectResponse
    {
        $user = User::name($request->input('username'))->firstOrFail();
        $group = Group::name($request->input('groupname'))->firstOrFail();

        $this->validate($request, ['reason' => 'max:255']);

        if ($request->input('everywhere') === '1') {
            foreach (user()->moderatedGroups as $group) {
                $ban = GroupBan::where('group_id', $group->id)->where('user_id', $user->id)->first();
                if (!$ban) {
                    $group->banUser($user, $request->input('reason'));
                }
            }
        } else {
            if (!user()->isModerator($group)) {
                abort(403, 'Access denied');
            }
            $ban = GroupBan::where('group_id', $group->id)->where('user_id', $user->id)->first();
            if (!$ban) {
                $group->banUser($user, $request->input('reason'));
            }
        }

        return $this->redirector->route('group_banned', $group);
    }

    public function removeBan(\Illuminate\Http\Request $request): JsonResponse
    {
        $ban = GroupBan::findOrFail($request->input('id'));

        if (!user()->isModerator($ban->group)) {
            abort(403, 'Access denied');
        }

        $ban->delete();

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
