<?php namespace Strimoid\Models;

class GroupBanned extends BaseModel
{
    protected $table = 'group_bans';

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function moderator()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }
}
