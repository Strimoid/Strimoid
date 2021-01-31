<?php

namespace Strimoid\Contracts\Repositories;

use Strimoid\Exceptions\EntityNotFoundException;
use Strimoid\Models\User;

interface UserRepository
{
    public function getByName(string $name): ?User;

    /** @throws EntityNotFoundException */
    public function requireByName(string $name): User;
}
