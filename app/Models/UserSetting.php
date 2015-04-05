<?php namespace Strimoid\Models;

/**
 * Strimoid\Models\UserSetting
 *
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class UserSetting extends BaseModel
{
    protected $table = 'user_settings';
    public $timestamps = false;

    protected static $unguarded = true;
}
