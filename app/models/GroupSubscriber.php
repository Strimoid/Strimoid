<?php

use Jenssegers\Mongodb\Model as Eloquent;

class GroupSubscriber extends Eloquent
{

    protected $collection = 'group_subscribers';

    public function group()
    {
        return $this->belongsTo('Group')->orderBy('name', 'asc');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

}