<?php

namespace Strimoid\Contracts\Repositories;

use Strimoid\Models\User as User;

interface UserRepository
{
    public function getByName(string $name): ?User;

    /** @throws \Strimoid\Exceptions\EntityNotFoundException */
    public function requireByName(string $name): User;
}
