<?php

use Jenssegers\Mongodb\Model as Eloquent;

class CommentReply extends BaseModel
{

    protected $attributes = array(
        'uv' => 0,
        'dv' => 0,
        'score' => 0,
    );

    protected static $rules = [
        'text' => 'required|min:1|max:5000'
    ];

    protected $appends = ['vote_state'];
    protected $hidden = ['text_source', 'updated_at'];
    protected $fillable = ['text'];

    function __construct($attributes = array())
    {
        $this->_id = Str::random(8);

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($reply)
        {
            // Increase comments counter in content
            //Content::where('_id', $reply->comment->content_id)->increment('comments');
        });
    }

    public static function find($id, $columns = array('*')) {
        $parent = Comment::where('_replies._id', $id)
            ->project(['_replies' => ['$elemMatch' => ['_id' => $id]]])
            ->first(['created_at', 'content_id', 'user_id', 'text', 'uv', 'dv', 'votes']);

        if (!$parent)
        {
            return null;
        }

        return $parent->replies->first();
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function delete()
    {
        Content::where('_id', $this->comment->content_id)->decrement('comments');
        $this->deleteNotifications();

        return parent::delete();
    }

    public function deleteNotifications()
    {
        Notification::where('comment_reply_id', $this->_id)->delete();
    }

    public function getGroupIdAttribute($value)
    {
        return $this->comment->group_id;
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function mpush($column, $value = null, $unique = false)
    {
        if (!$this->_id)
            return new Exception('Tried to push on model without id');

        $column = '_replies.$.'. $column;

        $builder = Comment::where('_id', $this->comment->_id)->where('_replies._id', $this->_id);
        $builder->push($column, $value, $unique);
    }

    public function mpull($column, $value = null)
    {
        if (!$this->_id)
            return new Exception('Tried to pull on model without id');

        $column = '_replies.$.'. $column;

        $builder = Comment::where('_id', $this->comment->_id)->where('_replies._id', $this->_id);
        $builder->pull($column, $value);
    }

    public function increment($column, $amount = 1) {
        $column = '_replies.$.'. $column;

        $builder = Comment::where('_id', $this->comment->_id)->where('_replies._id', $this->_id);
        $builder->increment($column, $amount);
    }

    public function decrement($column, $amount = 1) {
        $column = '_replies.$.'. $column;

        $builder = Comment::where('_id', $this->comment->_id)->where('_replies._id', $this->_id);
        $builder->decrement($column, $amount);
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
        return route('content_comments', $this->comment->content_id) .'#'. $this->_id;
    }

    public function canEdit(User $user)
    {
        return Auth::user()->_id == $this->user_id && $this == $this->comment->replies->last();
    }

    public function canRemove(User $user)
    {
        return Auth::user()->_id == $this->user_id || Auth::user()->isModerator($this->group_id);
    }

}