<?php namespace Strimoid\Models;

/**
 * Strimoid\Models\GroupBan
 *
 * @property-read \Strimoid\Models\Group $group 
 * @property-read \Strimoid\Models\User $user 
 * @property-read \Strimoid\Models\User $moderator 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class GroupBan extends BaseModel
{
    protected $table = 'group_bans';

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function moderator()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }
}
