<?php namespace Strimoid\Models;

class UserBlocked extends BaseModel
{
    protected $table = 'user_blocked_users';

    public function target()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User', 'source_id');
    }
}
