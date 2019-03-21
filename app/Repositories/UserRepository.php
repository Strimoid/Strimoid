<?php

namespace Strimoid\Repositories;

use Strimoid\Contracts\Repositories\UserRepository as UserRepositoryContract;
use Strimoid\Exceptions\EntityNotFoundException;
use Strimoid\Models\User;

class UserRepository implements UserRepositoryContract
{
    /**
     * @var User
     */
    protected $users;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->users = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getByName($name)
    {
        return $this->users->name($name)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function requireByName($name)
    {
        $user = $this->getByName($name);

        if (!$user) {
            throw new EntityNotFoundException();
        }

        return $user;
    }
}
