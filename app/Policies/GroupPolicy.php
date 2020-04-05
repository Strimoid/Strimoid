<?php

namespace Strimoid\Policies;

use Strimoid\Models\User;

class GroupPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }
}
