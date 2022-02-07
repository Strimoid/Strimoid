<?php

namespace Strimoid\Policies;

use Illuminate\Auth\Access\Response;
use Strimoid\Models\Content;
use Strimoid\Models\User;

class ContentPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function edit(User $user, Content $content): Response
    {
        if ($user->id !== $content->user->id) {
            return Response::deny('You are not an author of this content');
        }

        if ($content->created_at->diffInMinutes() > 30) {
            return Response::deny('Minął czas dozwolony na edycję treści');
        }

        return Response::allow();
    }

    public function remove(User $user, Content $content): bool
    {
        return $user->id === $content->user->id;
    }

    public function softRemove(User $user, Content $content): bool
    {
        return $user->isModerator($content->group_id);
    }
}
