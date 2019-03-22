<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\Builder;

class UserSetting extends BaseModel
{
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'user_settings';
    protected $primaryKey = ['key', 'user_id'];

    protected static $unguarded = true;

    /**
     * Set the keys for a save update query.
     *
     *
     */
    protected function setKeysForSaveQuery(Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        foreach ($this->getKeyName() as $key) {
            if (!$this->$key) {
                throw new \Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
            }

            $query->where($key, '=', $this->$key);
        }

        return $query;
    }
}
