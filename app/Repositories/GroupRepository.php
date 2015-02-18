<?php namespace Strimoid\Repositories; 

use Strimoid\Contracts\GroupRepository as GroupRepositoryContract;
use Strimoid\Exceptions\EntityNotFoundException;
use Strimoid\Models\Group;

class GroupRepository implements GroupRepositoryContract {

    /**
     * @var Group
     */
    protected $group;

    /**
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @inheritdoc
     */
    public function getByName($name)
    {
        $className = 'Strimoid\\Models\\Folders\\'. studly_case($name);

        if (class_exists($className))
        {
            return new $className;
        }

        return $this->group->shadow($name)->first();
    }

    /**
     * @inheritdoc
     */
    public function requireByName($name)
    {
        $group = $this->getByName($name);

        if ( ! $group)
        {
            throw new EntityNotFoundException;
        }

        return $group;
    }
}
