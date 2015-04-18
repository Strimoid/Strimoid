<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;

class NotificationTarget extends BaseModel
{
    use HasUserRelationship;

    protected $attributes = ['read' => false];

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
