<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\NoUpdatedAt;

class Vote extends BaseModel
{
    use HasUserRelationship, NoUpdatedAt;

    protected static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }

    public function setUpAttribute($value)
    {
        $this->attributes['up'] = (bool) $value;
    }
}
