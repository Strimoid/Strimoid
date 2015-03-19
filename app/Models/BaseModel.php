<?php namespace Strimoid\Models;

use Auth;
use Date;
use DateTimeZone;
use Hashids;
use Illuminate\Database\Eloquent\Model;
use Settings;
use Validator;

abstract class BaseModel extends Model
{
    /**
     * @var array
     */
    protected static $rules = [];

    public function createdAgo()
    {
        return Date::instance($this->created_at)->ago();
    }

    public function getLocalTime()
    {
        $timezone = new DateTimeZone(Settings::get('timezone'));

        return $this->created_at->setTimeZone($timezone)
            ->format('d/m/Y H:i:s');
    }

    public function getVoteState()
    {
        if (Auth::guest() || ! $this->votes()) {
            return 'none';
        }

        $vote = $this->votes()
            ->where('user_id', Auth::id())
            ->first();

        if (! $vote) {
            return 'none';
        }

        return $vote->up ? 'uv' : 'dv';
    }

    public function getVoteStateAttribute()
    {
        return $this->getVoteState();
    }

    public function votes()
    {
        return $this->embedsMany('Vote', 'votes');
    }

    public function saves()
    {
        return $this->embedsMany('Save', 'saves');
    }

    public function isSaved(User $user = null)
    {
        if (! $user) {
            if (Auth::guest()) {
                return false;
            }
            $user = Auth::user();
        }

        return $this->saves()
            ->where('user_id', $user->getKey())
            ->first();
    }

    public static function rules()
    {
        return static::$rules;
    }

    public function parent()
    {
        return $this->getParentRelation()->getParent();
    }

    public function hashId()
    {
        return Hashids::encode($this->getKey());
    }

    /* Scopes */

    public function scopeFromDaysAgo($query, $days)
    {
        $fromTime = Date::now()->subDays($days)
            ->hour(0)->minute(0)->second(0);
        $query->where('created_at', '>', $fromTime);
    }
}
