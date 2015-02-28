<?php namespace Strimoid\Models;

class Vote extends BaseModel
{
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
     * Updated at field is not needed in this model,
     * so just don't do anything.
     *
     * @param mixed $value
     */
    public function setUpdatedAt($value)
    {
    }
}
