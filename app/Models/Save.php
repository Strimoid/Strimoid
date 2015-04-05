<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\NoUpdatedAt;

/**
 * Strimoid\Models\Save
 *
 * @property-read \ $element 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @property-read User $user 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class Save extends BaseModel
{
    use HasUserRelationship, NoUpdatedAt;

    public static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }
}
