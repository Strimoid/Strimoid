<?php namespace Strimoid\Models\Traits;

use Strimoid\Models\User;

trait HasUserRelationship
{
    /**
     * User relationship.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
