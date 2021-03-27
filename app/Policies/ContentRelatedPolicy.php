<?php

namespace Strimoid\Policies;

use Strimoid\Models\ContentRelated;
use Strimoid\Models\User;

class ContentRelatedPolicy
{
    public function edit(User $user, ContentRelated $related): bool
    {
        return $user->id === $related->user->id;
    }

    public function remove(User $user, ContentRelated $related): bool
    {
        return $user->id === $related->user->id || $user->isModerator($related->group_id);
    }
}
