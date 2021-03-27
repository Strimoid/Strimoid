<?php

namespace Strimoid\Policies;

use Strimoid\Models\CommentReply;
use Strimoid\Models\User;

class CommentReplyPolicy
{
    public function edit(User $user, CommentReply $reply): bool
    {
        return $user->id === $reply->user->id && $reply->isLast();
    }

    public function remove(User $user, CommentReply $reply): bool
    {
        return $user->id === $reply->user->id || $user->isModerator($reply->group_id);
    }
}
