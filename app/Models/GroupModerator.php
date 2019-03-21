<?php

namespace Strimoid\Models;

class GroupModerator extends BaseModel
{
    protected $table = 'group_moderators';

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }
}
