<?php

namespace Strimoid\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Strimoid\Models\Group;

trait HasGroupRelationship
{
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
