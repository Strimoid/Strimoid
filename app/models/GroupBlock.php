<?php

use Jenssegers\Mongodb\Model as Eloquent;

class GroupBlock extends Eloquent
{

    protected $collection = 'group_blocks';

    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

}
