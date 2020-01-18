<?php

namespace Strimoid\Repositories;

use Strimoid\Contracts\Repositories\UserRepository as UserRepositoryContract;
use Strimoid\Exceptions\EntityNotFoundException;
use Strimoid\Models\User;

class UserRepository implements UserRepositoryContract
{
    protected User $users;

    public function __construct(User $user)
    {
        $this->users = $user;
    }

    public function getByName(string $name): ?User
    {
        return $this->users->name($name)->first();
    }

    public function requireByName(string $name): User
    {
        $user = $this->getByName($name);

        if (!$user) {
            throw new EntityNotFoundException();
        }

        return $user;
    }
}
