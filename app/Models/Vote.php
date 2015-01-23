<?php namespace Strimoid\Models;

class Vote extends BaseModel {

    protected static $unguarded = true;

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function setUpAttribute($value)
    {
        $this->attributes['up'] = (bool) $value;
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    public function getDates()
    {
        return [static::CREATED_AT];
    }

}
