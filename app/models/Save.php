<?php namespace Strimoid\Models;

class Save extends BaseModel {

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

}
