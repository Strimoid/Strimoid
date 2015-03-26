<?php namespace Strimoid\Models;

use Auth;
use Date;
use DateTimeZone;
use Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Setting;

abstract class BaseModel extends Model
{
    /**
     * @var array Validation rules
     */
    protected static $rules = [];

    protected $vote_state;

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
     * Get vote state of current user.
     *
     * @return string none|uv|dv
     */
    public function getVoteState()
    {
        if (Auth::guest() || ! $this->votes()) {
            return 'none';
        }

        $vote = $this->vote;

        if (!$vote) return 'none';

        return $vote->up ? 'uv' : 'dv';
    }

    /**
     * Attribute alias for vote state.
     *
     * @return string
     */
    public function getVoteStateAttribute()
    {
        return $this->getVoteState();
    }

    /**
     * Object votes relationship.
     *
     * @return mixed
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'element');
    }

    /**
     * Currently authenticated user vote.
     *
     * @return mixed
     */
    public function vote()
    {
        return $this->morphOne(Vote::class, 'element')->where('user_id', Auth::id());
    }

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
        if (Auth::guest()) return false;

        return (bool) $this->usave;
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
     * Get parent object.
     *
     * @return mixed
     */
    public function parent()
    {
        return $this->getParentRelation()->getParent();
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
