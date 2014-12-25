<?php namespace Strimoid\Models;

class GroupBlock extends BaseModel
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
