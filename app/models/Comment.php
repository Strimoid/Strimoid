<?php namespace Strimoid\Models;

use Auth, Str;

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

    protected $appends = ['vote_state'];
    protected $table = 'comments';
    protected $fillable = ['text'];
    protected $hidden = ['_replies', 'content_id', 'text_source', 'updated_at'];

    function __construct($attributes = array())
    {
        $this->id = Str::random(6);

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($comment)
        {
            // Increase comments counter in content
            $comment->content->increment('comments_count');
        });
    }

    public function content()
    {
        return $this->belongsTo('Strimoid\Models\Content')
            ->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function replies()
    {
        return $this->embedsMany('Strimoid\Models\CommentReply', '_replies')
            ->with('user');
    }

    public function delete()
    {
        foreach ($this->replies as $reply)
        {
            $reply->delete();
        }

        Notification::where('comment_id', $this->getKey())->delete();
        Content::where('_id', $this->content_id)->decrement('comments_count');

        return parent::delete();
    }

    public function deleteNotifications()
    {
        Notification::where('comment_id', $this->getKey())->delete();
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text(parse_usernames($text));
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