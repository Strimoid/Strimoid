<?php

namespace Strimoid\Models\Traits;

use Strimoid\Models\Group;

trait HasGroupRelationship
{
    /**
     * Group relationship.
     *
     * @return mixed
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
