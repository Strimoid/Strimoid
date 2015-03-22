<?php namespace Strimoid\Models\Traits;

trait HasGroupRelationship
{
    /**
     * Group relationship.
     *
     * @return mixed
     */
    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }
}
