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

        parent::boot();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class);
    }

    public function delete()
    {
        Content::where('id', $this->comment->content_id)->decrement('comments_count');

        return parent::delete();
    }

    public function isHidden()
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->isBlockingUser($this->user);
    }

    public function getURL()
    {
        $url = route('content_comments', $this->parent->content);
        return $url.'#'.$this->hashId();
    }

    public function canEdit()
    {
        return Auth::id() == $this->user_id
            && $this == $this->parent->replies->last();
    }

    public function canRemove()
    {
        return Auth::id() == $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
