<?php

namespace Strimoid\Http\Controllers\Group;

use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Group;
use Strimoid\Models\GroupModerator;
use Strimoid\Models\ModeratorAction;
use Strimoid\Models\User;

class ModeratorController extends BaseController
{
    public function __construct(private readonly \Illuminate\Contracts\View\Factory $viewFactory, private readonly \Illuminate\Routing\Redirector $redirector, private readonly \Illuminate\Cache\CacheManager $cacheManager, private readonly \Illuminate\Auth\AuthManager $authManager, private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }
    public function showModeratorList($group)
    {
        $moderators = GroupModerator::where('group_id', $group->getKey())
            ->orderBy('created_at', 'asc')
            ->with('user')
            ->paginate(25);

        return $this->viewFactory->make('group.moderators', compact('group', 'moderators'));
    }

    public function addModerator(\Illuminate\Http\Request $request)
    {
        $group = Group::name($request->input('groupname'))->firstOrFail();
        $user = User::name($request->input('username'))->firstOrFail();

        if (!user()->isAdmin($group)) {
            abort(403, 'Access denied');
        }

        if ($user->isModerator($group)) {
            return $this->redirector->route('group_moderators', $group->urlname);
        }

        if ($user->isBlocking($group)) {
            return $this->redirector->route('group_moderators', $group->urlname)
                ->with('danger_msg', 'Nie możesz dodać wybranego użytkownika jako moderatora, ponieważ zablokował tą grupę.');
        }

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate($user);

        $type = $request->input('admin') === 'on' ? 'admin' : 'moderator';
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
        $action->is_admin = $moderator->type === 'admin';
        $action->moderator()->associate(user());
        $action->target()->associate($user);
        $action->group()->associate($group);
        $action->save();

        $this->cacheManager->tags(['user.moderated-groups', 'u.' . $user->getKey()])->flush();

        return $this->redirector->route('group_moderators', $group->urlname);
    }

    public function removeModerator(\Illuminate\Http\Request $request)
    {
        $moderator = GroupModerator::findOrFail($request->input('id'));
        $group = $moderator->group;

        if (!user()->isAdmin($moderator->group)) {
            abort(403, 'Access denied');
        }

        if ($moderator->user_id === $group->creator_id && $this->authManager->id() !== $group->creator_id) {
            return $this->responseFactory->json(['status' => 'error']);
        }

        $moderator->delete();

        // Log this action
        $action = new ModeratorAction();
        $action->type = ModeratorAction::TYPE_MODERATOR_REMOVED;
        $action->is_admin = $moderator->type === 'admin';
        $action->moderator()->associate(user());
        $action->target()->associate($moderator);
        $action->group()->associate($group);
        $action->save();

        $this->cacheManager->tags(['user.moderated-groups', 'u.' . $moderator->user_id])->flush();

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
