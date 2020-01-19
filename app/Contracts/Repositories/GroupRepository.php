<?php

namespace Strimoid\Contracts\Repositories;

interface GroupRepository
{
    public function getByName(string $name);

    /** @throws \Strimoid\Exceptions\EntityNotFoundException */
    public function requireByName(...$params);
}
