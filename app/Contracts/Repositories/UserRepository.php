<?php

namespace Strimoid\Contracts\Repositories;

interface UserRepository
{
    /**
     * Get user with given name.
     *
     * @param  $name  string  User name
     *
     */
    public function getByName($name): \Strimoid\Models\User;

    /**
     * Get user with given name and throw
     * exception if not found.
     *
     * @param  $name  string  User name
     *
     * @throws \Strimoid\Exceptions\EntityNotFoundException
     *
     */
    public function requireByName($name): void;
}
