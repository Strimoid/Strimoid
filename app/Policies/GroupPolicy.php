<?php

namespace Strimoid\Policies;

class GroupPolicy
{
    public function create(): bool
    {
        return true;
    }
}
