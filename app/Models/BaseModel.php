<?php namespace Strimoid\Models;

use Date;
use DateTimeZone;
use Eloquent;
use Hashids;
use Setting;
use Watson\Rememberable\Rememberable;

abstract class BaseModel extends Eloquent
{
    use Rememberable;

    /**
     * @var array Validation rules
     */
    protected static $rules = [];

    /**
     * Get time ago from object creation.
     *
     * @return string
     */
    public function createdAgo()
    {
        return Date::instance($this->created_at)->ago();
    }

    /**
     * Get formatted time, converted to current user timezone.
     *
     * @return mixed
     */
    public function getLocalTime()
    {
        $timezone = new DateTimeZone(Setting::get('timezone', 'Europe/Warsaw'));

        return $this->created_at->setTimeZone($timezone)
            ->format('d/m/Y H:i:s');
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public static function rules()
    {
        return static::$rules;
    }

    /**
     * Get hashed object id.
     *
     * @return string
     */
    public function hashId()
    {
        return Hashids::encode($this->getKey());
    }

    /**
     * Get the value of the model's route key.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return $this->hashId();
    }

    /* Scopes */

    /**
     * Filter by created time ago.
     *
     * @param $query
     * @param $days
     */
    public function scopeFromDaysAgo($query, $days)
    {
        $fromTime = Date::now()->subDays($days)
            ->hour(0)->minute(0)->second(0);
        $query->where('created_at', '>', $fromTime);
    }
}
