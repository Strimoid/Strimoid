<?php

namespace Strimoid\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\Vote;

trait HasVotes
{
    /**
     * Votes relationship.
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'element');
    }

    /**
     * Currently authenticated user vote.
     *
     */
    public function vote(): MorphOne
    {
        return $this->morphOne(Vote::class, 'element')->where('user_id', Auth::id());
    }

    /**
     * Get vote state of current user.
     */
    public function getVoteState(): string
    {
        if (Auth::guest() || !$this->votes()) {
            return 'none';
        }

        $vote = $this->vote;

        if (!$vote) {
            return 'none';
        }

        return $vote->up ? 'uv' : 'dv';
    }

    public function getVoteStateAttribute(): string
    {
        return $this->getVoteState();
    }
}
