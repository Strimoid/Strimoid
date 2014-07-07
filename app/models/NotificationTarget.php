<?php

class NotificationTarget extends BaseModel
{

    protected $attributes = array(
        'read' => false,
    );

    public function user()
    {
        return $this->hasOne('User');
    }

    public function setReadAttribute($value)
    {
        $this->attributes['read'] = toBool($value);
    }

}
