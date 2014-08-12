<?php

class UserData extends BaseModel {

    public $timestamps = false;
    protected $collection = 'user_data';

    public function savedContents()
    {
        return $this->belongsToMany('Content', null, '_id', 'contents');
    }

}
