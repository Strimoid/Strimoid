<?php

namespace Strimoid\Models\Traits;

use Strimoid\Models\User;

trait HasUserRelationship
{
    /**
     * User relationship.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
