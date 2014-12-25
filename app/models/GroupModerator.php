<?php namespace Strimoid\Models;

class GroupModerator extends BaseModel
{

    protected $table = 'group_moderators';

    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

}