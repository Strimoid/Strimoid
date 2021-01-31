<?php

namespace Strimoid\Models;

class GroupBan extends BaseModel
{
    protected $table = 'group_bans';

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class);
    }
}
