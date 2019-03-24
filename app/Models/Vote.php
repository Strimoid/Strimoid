<?php

namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\NoUpdatedAt;

class Vote extends BaseModel
{
    use HasUserRelationship, NoUpdatedAt;

    public $incrementing = false;
    protected $primaryKey = null;

    protected static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }
}
