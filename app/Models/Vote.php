<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\NoUpdatedAt;

/**
 * Strimoid\Models\Vote
 *
 * @property-read \ $element 
 * @property-write mixed $up 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @property-read User $user 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class Vote extends BaseModel
{
    use HasUserRelationship, NoUpdatedAt;

    protected static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }

    public function setUpAttribute($value)
    {
        $this->attributes['up'] = (bool) $value;
    }
}
