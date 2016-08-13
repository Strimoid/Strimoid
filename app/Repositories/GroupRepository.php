<?php namespace Strimoid\Repositories;

use Strimoid\Contracts\Repositories\GroupRepository as GroupRepositoryContract;
use Strimoid\Models\Group;

class GroupRepository extends Repository implements GroupRepositoryContract
{
    /**
     * @var Group
     */
    protected $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @inheritdoc
     */
    public function getByName($name)
    {
        $className = 'Strimoid\\Models\\Folders\\'.studly_case($name);

        if (class_exists($className)) {
            return new $className();
        }

        return $this->group->name($name)->first();
    }
}
