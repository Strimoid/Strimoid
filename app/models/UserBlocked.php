<?php namespace Strimoid\Models;

class UserBlocked extends BaseModel
{

    protected $table = 'user_blocks';

    public function target()
    {
        return $this->belongsTo('User');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

}
