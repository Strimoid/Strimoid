<?php namespace Strimoid\Http\Controllers\Group;

use Auth;
use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Group;
use Strimoid\Models\GroupModerator;
use Strimoid\Models\ModeratorAction;
use Strimoid\Models\User;

class ModeratorController extends BaseController
{
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
        $group = Group::name(request('groupname'))->firstOrFail();
        $user = User::name(request('username'))->firstOrFail();

        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        if ($user->isModerator($group)) {
            return redirect()->route('group_moderators', $group->urlname);
        }

        if ($user->isBlocking($group)) {
            return redirect()->route('group_moderators', $group->urlname)
                ->with('danger_msg', 'Nie możesz dodać wybranego użytkownika jako moderatora, ponieważ zablokował tą grupę.');
        }

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate($user);

        $type = request('admin') == 'on' ? 'admin' : 'moderator';
        $moderator->type = $type;

        $moderator->save();

        // Send notification to new moderator
        /*
        $this->sendNotifications([$user->getKey()], function ($notification) use ($moderator, $group) {
            $notification->type = 'moderator';

            $positionTitle = $moderator->type == 'admin' ? 'administratorem' : 'moderatorem';
            $notification->setTitle('Zostałeś ' . $positionTitle . ' w grupie ' . $group->urlname);

            $notification->group()->associate($group);
            $notification->save();
        });
        */

        // Log this action
        $action = new ModeratorAction();
        $action->type = ModeratorAction::TYPE_MODERATOR_ADDED;
        $action->is_admin = $moderator->type == 'admin' ? true : false;
        $action->moderator()->associate(user());
        $action->target()->associate($user);
        $action->group()->associate($group);
        $action->save();

        return redirect()->route('group_moderators', $group->urlname);
    }

    public function removeModerator()
    {
        $moderator = GroupModerator::findOrFail(request('id'));
        $group = $moderator->group;

        if (!user()->isAdmin($moderator->group)) {
            abort(403, 'Access denied');
        }

        if ($moderator->user_id == $group->creator_id && Auth::id() != $group->creator_id) {
            return response()->json(['status' => 'error']);
        }

        $moderator->delete();

        // Log this action
        $action = new ModeratorAction();
        $action->type = ModeratorAction::TYPE_MODERATOR_REMOVED;
        $action->is_admin = $moderator->type == 'admin' ? true : false;
        $action->moderator()->associate(user());
        $action->target()->associate($moderator);
        $action->group()->associate($group);
        $action->save();

        return response()->json(['status' => 'ok']);
    }
}
