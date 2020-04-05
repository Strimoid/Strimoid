<?php

namespace Strimoid\Policies;

use Strimoid\Models\User;

class CommentPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }
}
