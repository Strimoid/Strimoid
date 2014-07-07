<?php

use Jenssegers\Mongodb\Model as Eloquent;

class Comment extends BaseModel
{

    protected $attributes = [
        'uv' => 0,
        'dv' => 0,
        'score' => 0,
    ];

    protected static $rules = [
        'text' => 'required|min:1|max:5000'
    ];

    protected $collection = 'comments';
    protected $fillable = ['text'];
    protected $hidden = ['content_id', 'text_source', 'updated_at'];

    function __construct($attributes = array())
    {
        $this->_id = Str::random(6);

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($comment)
        {
            // Increase comments counter in content
            $comment->content->increment('comments');
        });
    }

    public function content()
    {
        return $this->belongsTo('Content')->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function replies()
    {
        return $this->embedsMany('CommentReply', '_replies');
    }

    public function delete()
    {
        foreach ($this->replies as $reply)
        {
            $reply->delete();
        }

        Notification::where('comment_id', $this->getKey())->delete();
        Content::where('_id', $this->content_id)->decrement('comments');

        return parent::delete();
    }

    public function deleteNotifications()
    {
        Notification::where('comment_id', $this->getKey())->delete();
    }

    public function setTextAttribute($text)
    {
        $parser = Parsedown::instance();

        $this->attributes['text'] = $parser->parse(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function isHidden()
    {
        if (Auth::guest())
        {
            return false;
        }

        return Auth::user()->isBlockingUser($this->user);
    }

    public function getURL()
    {
        return route('content_comments', $this->content_id) .'#'. $this->_id;
    }

    public function canEdit(User $user)
    {
        return Auth::user()->_id == $this->user_id && $this->replies()->count() == 0;
    }

    public function canRemove(User $user)
    {
        return Auth::user()->_id == $this->user_id || Auth::user()->isModerator($this->group_id);
    }

}