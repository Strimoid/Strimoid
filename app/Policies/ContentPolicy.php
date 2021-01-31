<?php

namespace Strimoid\Policies;

use Strimoid\Models\Content;
use Strimoid\Models\User;

class ContentPolicy
{
    public function edit(User $user, Content $content): bool
    {
        return $user->id === $content->user->id;
    }

    public function remove(User $user, Content $content): bool
    {
        return $user->id === $content->user->id || $user->isModerator($content->group_id);
    }
}
