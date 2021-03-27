<?php

namespace Strimoid\Policies;

use Strimoid\Models\User;

class GroupPolicy
{
    public function create(): bool
    {
        return true;
    }
}
