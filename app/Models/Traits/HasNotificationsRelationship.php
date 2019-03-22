<?php

namespace Strimoid\Models\Traits;

use Strimoid\Models\Notification;

trait HasNotificationsRelationship
{
    /**
     * Notifications relationship.
     *
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'element');
    }
}
