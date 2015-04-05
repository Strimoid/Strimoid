<?php namespace Strimoid\Models;

/**
 * Strimoid\Models\GroupModerator
 *
 * @property-read \Strimoid\Models\Group $group 
 * @property-read \Strimoid\Models\User $user 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class GroupModerator extends BaseModel
{
    protected $table = 'group_moderators';

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }
}
