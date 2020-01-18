<?php

namespace Strimoid\Models;

class DailyAction extends BaseModel
{
    protected $table = 'daily_actions';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPointsAttribute($value): int
    {
        return (int) $value;
    }

    public function getContentsAttribute($value): int
    {
        return (int) $value;
    }

    public function getCommentsAttribute($value): int
    {
        return (int) $value;
    }

    public function getEntriesAttribute($value): int
    {
        return (int) $value;
    }

    public function getUvAttribute($value): int
    {
        return (int) $value;
    }

    public function getDvAttribute($value): int
    {
        return (int) $value;
    }
}
