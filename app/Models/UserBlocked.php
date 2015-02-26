<?php namespace Strimoid\Models;

class UserBlocked extends BaseModel
{
    protected $table = 'user_blocks';

    public function target()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }
}
