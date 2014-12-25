<?php namespace Strimoid\Models;

class GroupBanned extends BaseModel
{

    protected $table = 'group_bans';

    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function moderator()
    {
        return $this->belongsTo('User');
    }

}
