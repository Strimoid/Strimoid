<?php namespace Strimoid\Models;

/**
 * Strimoid\Models\NotificationTarget
 *
 * @property-read \Strimoid\Models\User $user 
 * @property-write mixed $read 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class NotificationTarget extends BaseModel
{
    protected $attributes = ['read' => false];

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function setReadAttribute($value)
    {
        $this->attributes['read'] = toBool($value);
    }
}
