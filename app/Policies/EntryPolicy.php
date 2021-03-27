<?php

namespace Strimoid\Policies;

use Illuminate\Auth\Access\Response;
use Strimoid\Models\Entry;
use Strimoid\Models\User;

class EntryPolicy
{
    public function edit(User $user, Entry $entry): Response
    {
        if ($entry->replies()->count() !== 0) {
            return Response::deny('Pojawiła się już odpowiedź na twój wpis');
        }

        if ($user->id !== $entry->user->id) {
            return Response::deny('You do not own this entry');
        }

        return Response::allow();
    }

    public function remove(User $user, Entry $entry): bool
    {
        return $user->id === $entry->user->id || $user->isModerator($entry->group_id);
    }
}
