<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasNotificationsRelationship;

class EntryReply extends Entry
{
    use HasNotificationsRelationship;

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

        static::deleted(function ($reply) {
            $reply->parent->decrement('replies_count');
        });

        parent::boot();
    }

    public function parent()
    {
        return $this->belongsTo(Entry::class);
    }

    public function isLast()
    {
        $lastId = $this->parent->replies()
            ->orderBy('created_at', 'desc')
            ->value('id');

        return $lastId == $this->getKey();
    }

    public function getURL()
    {
        return route('single_entry', $this->parent_id).'#'.$this->hashId();
    }

    public function canEdit()
    {
        return auth()->id() === $this->user_id && $this->isLast();
    }

    public function canRemove()
    {
        return auth()->id() === $this->user_id || user()->isModerator($this->group_id);
    }
}
