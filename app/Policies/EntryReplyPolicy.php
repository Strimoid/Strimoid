<?php

namespace Strimoid\Policies;

use Illuminate\Auth\Access\Response;
use Strimoid\Models\EntryReply;
use Strimoid\Models\User;

class EntryReplyPolicy
{
    public function edit(User $user, EntryReply $reply): Response
    {
        if (!$reply->isLast()) {
            return Response::deny('Pojawiła się już odpowiedź na twój wpis');
        }

        if ($user->id !== $reply->user->id) {
            return Response::deny('You do not own this entry reply');
        }

        return Response::allow();
    }

    public function remove(User $user, EntryReply $reply): bool
    {
        return $user->id === $reply->user->id || $user->isModerator($reply->group_id);
    }
}
