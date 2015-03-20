<?php namespace Strimoid\Models\Traits;

trait HasGroup
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
