<?php namespace Strimoid\Contracts\Repositories;

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
     * @throws \Strimoid\Exceptions\EntityNotFoundException
     * @return mixed
     */
    public function requireByName($name);

}
