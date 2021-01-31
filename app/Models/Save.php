<?php

namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;

class Save extends BaseModel
{
    use HasUserRelationship;

    public const UPDATED_AT = null;

    public static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }
}
