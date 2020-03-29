<?php

namespace Strimoid\Repositories;

use Illuminate\Support\Str;
use Strimoid\Contracts\Repositories\GroupRepository as GroupRepositoryContract;
use Strimoid\Models\Group;

class GroupRepository extends Repository implements GroupRepositoryContract
{
    protected Group $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function getByName(string $name)
    {
        $className = 'Strimoid\\Models\\Folders\\' . Str::studly($name);

        if (class_exists($className)) {
            return new $className();
        }

        return $this->group->name($name)->first();
    }
}
