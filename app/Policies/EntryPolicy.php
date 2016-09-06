<?php

namespace Strimoid\Policies;

use Strimoid\Models\Entry;
use Strimoid\Models\User;

class EntryPolicy
{
    public function update(User $user, Entry $entry)
    {
        return $user->id === $entry->user->id;
    }

    public function delete(User $user, Entry $entry)
    {
        return $user->id === $entry->user->id;
    }
}
