<?php

namespace Strimoid\Policies;

use Strimoid\Models\Content;
use Strimoid\Models\User;

class ContentPolicy
{
    public function update(User $user, Content $content)
    {
        return $user->id === $content->user->id;
    }

    public function delete(User $user, Content $content)
    {
        return $user->id === $content->user->id;
    }
}
