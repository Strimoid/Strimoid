<?php

namespace Strimoid\Policies;

use Illuminate\Auth\Access\Response;
use Strimoid\Models\Entry;
use Strimoid\Models\User;

class EntryPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    public function edit(User $user, Entry $entry): Response
    {
        if ($entry->replies_count !== 0) {
            return Response::deny();
        }

        if ($user->id !== $entry->user->id) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function delete(User $user, Entry $entry)
    {
        return $user->id === $entry->user->id;
    }
}
