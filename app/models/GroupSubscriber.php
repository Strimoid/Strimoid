<?php namespace Strimoid\Models;

class GroupSubscriber extends BaseModel
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