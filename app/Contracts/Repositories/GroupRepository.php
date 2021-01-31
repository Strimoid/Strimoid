<?php

namespace Strimoid\Contracts\Repositories;

use Strimoid\Exceptions\EntityNotFoundException;

interface GroupRepository
{
    public function getByName(string $name);

    /** @throws EntityNotFoundException */
    public function requireByName(...$params);
}
