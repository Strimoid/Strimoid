<?php namespace Strimoid\Models;

use Carbon\Carbon;
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
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     * @return \Jenssegers\Date\Date
     */
    protected function asDateTime($value)
    {
        $value = parent::asDateTime($value);
        return Date::instance($value);
    }

    /**
     * Get time ago from object creation.
     *
     * @return string
     */
    public function createdAgo()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get formatted time, converted to current user timezone.
     *
     * @return mixed
     */
    public function getLocalTime()
    {
        $timezone = Setting::get('timezone');
        $timezone = new DateTimeZone($timezone);

        return $this->created_at->setTimeZone($timezone)->format('d/m/Y H:i:s');
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
        $fromTime = Carbon::now()->subDays($days)
            ->hour(0)->minute(0)->second(0);
        $query->where('created_at', '>', $fromTime->toDateTimeString());
    }
}
