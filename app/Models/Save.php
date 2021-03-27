<?php

namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;

class Save extends BaseModel
{
    use HasUserRelationship;

    public const UPDATED_AT = null;

    public $incrementing = false;
    protected $primaryKey = null;

    protected static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }
}
