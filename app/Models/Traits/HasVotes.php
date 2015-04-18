<?php namespace Strimoid\Models\Traits;

use Auth;
use Strimoid\Models\Vote;

trait HasVotes
{
    /**
     * @var  string  uv|dv|none
     */
    protected $vote_state;

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

        if (!$vote) {
            return 'none';
        }

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
}
