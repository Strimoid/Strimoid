<?php

namespace Strimoid\Contracts\Repositories;

use Strimoid\Models\Group as Group;

interface GroupRepository
{
    public function getByName(string $name);

    /** @throws \Strimoid\Exceptions\EntityNotFoundException */
    public function requireByName(...$params);
}
