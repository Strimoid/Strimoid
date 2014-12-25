<?php namespace Strimoid\Models;

class GroupModerator extends BaseModel
{

    protected $collection = 'group_moderators';

    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function getLocalTime()
    {
        return $this->created_at->setTimeZone(new DateTimeZone('Europe/Warsaw'))->format('d/m/Y H:i:s');
    }

}