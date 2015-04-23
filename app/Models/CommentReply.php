<?php namespace Strimoid\Models;

use Auth;

class CommentReply extends Comment
{
    protected static $rules = [
        'text' => 'required|min:1|max:5000',
    ];

    protected $appends = ['vote_state'];
    protected $hidden = ['text_source', 'updated_at'];
    protected $fillable = ['text'];
    protected $table = 'comment_replies';

    public static function boot()
    {
        static::creating(function ($comment) {
            $comment->group_id = $comment->parent->group_id;
        });

        static::created(function ($reply) {
            $reply->parent->content->increment('comments_count');
        });

        static::deleted(function ($reply) {
            $reply->parent->content->decrement('comments_count');
        });

        static::bootTraits();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class);
    }

    public function isLast()
    {
        $lastId = $this->parent->replies()
            ->orderBy('created_at', 'desc')
            ->pluck('id');
        return $lastId == $this->getKey();
    }

    public function getURL()
    {
        $url = route('content_comments', $this->parent->content);
        return $url.'#'.$this->hashId();
    }

    public function canEdit()
    {
        return Auth::id() == $this->user_id && $this->isLast();
    }

    public function canRemove()
    {
        return Auth::id() == $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
