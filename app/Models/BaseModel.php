<?php

namespace Strimoid\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Strimoid\Settings\Facades\Setting;
use Vinkla\Hashids\Facades\Hashids;
use Watson\Rememberable\Rememberable;

abstract class BaseModel extends Model
{
    use Rememberable;

    protected static array $rules = [];

    /**
     * Return a timestamp as DateTime object.
     */
    protected function asDateTime($value): CarbonInterface
    {
        $value = parent::asDateTime($value);

        return Date::instance($value);
    }

    public function createdAgo(): string
    {
        return $this->created_at->diffForHumans();
    }

    /** Get formatted time, converted to current user timezone */
    public function getLocalTime(): string
    {
        $timezone = Setting::get('timezone');
        $timezone = new DateTimeZone($timezone);

        return $this->created_at->setTimeZone($timezone)->format('d/m/Y H:i:s');
    }

    public static function validationRules(): array
    {
        return static::$rules;
    }

    public function hashId(): string
    {
        return Hashids::encode($this->getKey());
    }

    public function getHashIdAttribute(): string
    {
        return $this->hashId();
    }

    public function getRouteKey(): string
    {
        return $this->hashId();
    }

    public function toArray(): array
    {
        $serialized = parent::toArray();

        if (array_key_exists('id', $serialized)) {
            $serialized['id'] = $this->hashId();
        }

        return $serialized;
    }

    /* Scopes */

    public function scopeFromDaysAgo($query, $days): void
    {
        $days = (int) $days;

        $fromTime = Carbon::now()->subDays($days)
            ->hour(0)->minute(0)->second(0);
        $query->where('created_at', '>', $fromTime->toDateTimeString());
    }

    public function scopeUserCache($query, string $tag, int $minutes = 60): void
    {
        $tags = ['user.' . $tag, 'u.' . auth()->id()];
        $query->remember($minutes)->cacheTags($tags);
    }
}
