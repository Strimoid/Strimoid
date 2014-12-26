<?php namespace Strimoid\Models;

class GroupSubscriber extends BaseModel
{

    protected $table = 'group_subscribers';

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group')
            ->orderBy('name', 'asc');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

}