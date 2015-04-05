<?php namespace Strimoid\Models;

/**
 * Strimoid\Models\DailyAction
 *
 * @property-read \Strimoid\Models\User $user 
 * @property-read mixed $points 
 * @property-read mixed $contents 
 * @property-read mixed $comments 
 * @property-read mixed $entries 
 * @property-read mixed $uv 
 * @property-read mixed $dv 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class DailyAction extends BaseModel
{
    protected $table = 'daily_actions';
    protected $connection = 'stats';

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function getPointsAttribute($value)
    {
        return intval($value);
    }

    public function getContentsAttribute($value)
    {
        return intval($value);
    }

    public function getCommentsAttribute($value)
    {
        return intval($value);
    }

    public function getEntriesAttribute($value)
    {
        return intval($value);
    }

    public function getUvAttribute($value)
    {
        return intval($value);
    }

    public function getDvAttribute($value)
    {
        return intval($value);
    }
}
