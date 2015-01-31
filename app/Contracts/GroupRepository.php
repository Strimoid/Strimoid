<?php namespace Strimoid\Contracts; 

interface GroupRepository {

    /**
     * Get group with given name.
     *
     * @param  $name  string  Group name
     * @return \Strimoid\Models\Group
     */
    public function getByName($name);

}
