<?php namespace Strimoid\Models\Traits;

trait HasUser
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
