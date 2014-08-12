<?php

use Jenssegers\Mongodb\Model as Eloquent;

class UserBlocked extends Eloquent
{

    protected $collection = 'user_blocks';

    public function target()
    {
        return $this->belongsTo('User');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

}
