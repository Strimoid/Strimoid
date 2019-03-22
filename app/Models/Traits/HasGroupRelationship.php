<?php

namespace Strimoid\Models\Traits;

use Strimoid\Models\Group;

trait HasGroupRelationship
{
    /**
     * Group relationship.
     *
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
