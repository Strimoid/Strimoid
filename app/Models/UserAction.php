<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\NoUpdatedAt;

/**
 * Strimoid\Models\UserAction
 *
 * @property-read \ $element 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @property-read User $user 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class UserAction extends BaseModel
{
    use HasUserRelationship, NoUpdatedAt;

    protected $table = 'user_actions';
    protected static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }
}
