<?php namespace Strimoid\Models\Traits;

trait HasUserRelationship
{
    /**
     * User relationship.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }
}
