<?php namespace Strimoid\Models;

class GroupBlock extends BaseModel
{

    protected $table = 'group_blocks';

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

}
