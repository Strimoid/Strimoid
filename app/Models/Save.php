<?php namespace Strimoid\Models;

class Save extends BaseModel {

    public static $unguarded = true;

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

}
