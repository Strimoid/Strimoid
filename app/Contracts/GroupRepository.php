<?php namespace Strimoid\Contracts; 

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
     * @throws EntityNotFoundException
     */
    public function requireByName($name);

}
