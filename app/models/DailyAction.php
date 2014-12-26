<?php namespace Strimoid\Models;

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
