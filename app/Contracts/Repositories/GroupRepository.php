<?php

namespace Strimoid\Contracts\Repositories;

interface GroupRepository
{
    /**
     * Get group with given name.
     *
     * @param  $name  string  Group name
     *
     */
    public function getByName($name): \Strimoid\Models\Group;

    /**
     * Get group with given name and throw
     * exception if not found.
     *
     *
     * @throws \Strimoid\Exceptions\EntityNotFoundException
     *
     */
    public function requireByName(...$params): \Strimoid\Models\Group;
}
