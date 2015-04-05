<?php namespace Strimoid\Models;

/**
 * Strimoid\Models\ModeratorAction
 *
 * @property-read \Strimoid\Models\User $moderator 
 * @property-read \Strimoid\Models\User $target 
 * @property-read \Strimoid\Models\Group $group 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class ModeratorAction extends BaseModel
{
    protected $table = 'moderator_actions';

    const TYPE_MODERATOR_ADDED      = 1;
    const TYPE_MODERATOR_REMOVED    = 2;
    const TYPE_SETTINGS_CHANGED     = 3;
    const TYPE_STYLE_CHANGED        = 4;

    public function moderator()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function target()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }
}
