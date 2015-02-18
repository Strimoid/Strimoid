<?php namespace Strimoid\Contracts\Repositories;

interface GroupRepository {

    /**
     * Get group with given name.
     *
     * @param  $name  string  Group name
     * @return \Strimoid\Models\Group
     */
    public function getByName($name);

    /**
     * Get group with given name and throw
     * exception if not found.
     *
     * @param  $name  string  Group name
     * @return \Strimoid\Models\Group
     * @throws \Strimoid\Exceptions\EntityNotFoundException
     */
    public function requireByName($name);

}
