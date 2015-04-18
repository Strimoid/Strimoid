<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\NoUpdatedAt;

class Save extends BaseModel
{
    use HasUserRelationship, NoUpdatedAt;

    public static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }
}
