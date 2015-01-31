<?php namespace Strimoid\Contracts;

use Strimoid\Exceptions\EntityNotFoundException;

interface UserRepository {

    /**
     * Get user with given name.
     *
     * @param  $name  string  User name
     * @return \Strimoid\Models\User
     */
    public function getByName($name);

    /**
     * Get user with given name and throw
     * exception if not found.
     *
     * @param  $name  string  User name
     * @throws EntityNotFoundException
     * @return mixed
     */
    public function requireByName($name);

}
