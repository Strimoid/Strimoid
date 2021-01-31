<?php

namespace Strimoid\Models\Traits;

use Illuminate\Support\Facades\Auth;
use Strimoid\Models\Save;

trait HasSaves
{
    /**
     * Object saves relationship.
     *
     */
    public function saves()
    {
        return $this->morphMany(Save::class, 'element');
    }

    /**
     * Currently authenticated user save.
     *
     */
    public function usave()
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

        return (bool) $this->usave;
    }
}
