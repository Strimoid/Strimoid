<?php namespace Strimoid\Models;

use Auth;

class EntryReply extends Entry
{
    protected static $rules = [
        'text' => 'required|min:1|max:2500',
    ];

    protected $appends = ['vote_state'];
    protected $fillable = ['text'];
    protected $hidden = ['entry_id', 'updated_at'];
    protected $table = 'entry_replies';

    public static function boot()
    {
        static::creating(function ($reply) {
            $reply->group_id = $reply->parent->group_id;
        });

        static::created(function ($reply) {
            $reply->parent->increment('replies_count');
        });

        parent::boot();
    }

    public function parent()
    {
        return $this->belongsTo(Entry::class);
    }

    public function delete()
    {
        Entry::where('id', $this->parent_id)->decrement('replies_count');

        return parent::delete();
    }

    public function isLast()
    {
        $lastReply = Entry::where('id', $this->parent->getKey())
            ->project(['_replies' => ['$slice' => -1]])
            ->first()->replies->first();

        return $lastReply->getKey() == $this->getKey();
    }

    public function getURL()
    {
        return route('single_entry', $this->parent).'#'.$this->hashId();
    }

    public function canEdit()
    {
        return Auth::id() === $this->user_id
            && $this == $this->parent->replies->last();
    }

    public function canRemove()
    {
        return Auth::id() === $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
