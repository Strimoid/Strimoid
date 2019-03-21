<?php

namespace Strimoid\Models\Traits;

use Auth;
use Strimoid\Models\Save;

trait HasSaves
{
    /**
     * Object saves relationship.
     *
     * @return mixed
     */
    public function saves()
    {
        return $this->morphMany(Save::class, 'element');
    }

    /**
     * Currently authenticated user save.
     *
     * @return mixed
     */
    public function usave()
    {
        return $this->morphOne(Save::class, 'element')->where('user_id', Auth::id());
    }

    /**
     * Check if object is saved by authenticated user.

     * @return bool
     */
    public function isSaved()
    {
        if (Auth::guest()) {
            return false;
        }

        return (bool) $this->usave;
    }
}
