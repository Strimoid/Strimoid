<?php namespace Strimoid\Models;

class NotificationTarget extends BaseModel
{

    protected $attributes = ['read' => false];

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function setReadAttribute($value)
    {
        $this->attributes['read'] = toBool($value);
    }

}
