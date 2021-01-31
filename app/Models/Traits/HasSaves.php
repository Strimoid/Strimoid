<?php

namespace Strimoid\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\Save;

trait HasSaves
{
    /**
     * Object saves relationship.
     */
    public function saves(): MorphMany
    {
        return $this->morphMany(Save::class, 'element');
    }

    /**
     * Currently authenticated user save.
     */
    public function userSave(): MorphOne
    {
        return $this->morphOne(Save::class, 'element')->where('user_id', Auth::id());
    }

    /**
     * Check if object is saved by authenticated user.
     */
    public function isSaved(): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return (bool) $this->userSave();
    }
}
