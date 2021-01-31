<?php

namespace Strimoid\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Strimoid\Models\User;

trait HasUserRelationship
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
