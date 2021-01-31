<?php

namespace Strimoid\Policies;

use Strimoid\Models\Comment;
use Strimoid\Models\User;

class CommentPolicy
{
    public function edit(User $user, Comment $comment)
    {
        return $user->id === $comment->user->id && $comment->replies()->count() === 0;
    }

    public function remove(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user->id || $user->isModerator($comment->group_id);
    }
}
