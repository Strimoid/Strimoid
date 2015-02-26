<?php namespace Strimoid\Models;

use Auth;
use Str;
use Strimoid\Helpers\MarkdownParser;

class CommentReply extends BaseModel
{
    protected $attributes = [
        'uv'    => 0,
        'dv'    => 0,
        'score' => 0,
    ];

    protected static $rules = [
        'text' => 'required|min:1|max:5000',
    ];

    protected $appends = ['vote_state'];
    protected $hidden = ['text_source', 'updated_at'];
    protected $fillable = ['text'];

    public function __construct($attributes = [])
    {
        $this->{$this->getKeyName()} = Str::random(8);

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->parent()->content->increment('comments_count');
        });
    }

    public static function find($id, $columns = ['*'])
    {
        $parent = Comment::where('_replies._id', $id)
            ->project(['_replies' => ['$elemMatch' => ['_id' => $id]]])
            ->first(['created_at', 'content_id', 'user_id', 'text', 'uv', 'dv', 'votes']);

        if (!$parent) {
            return;
        }

        return $parent->replies->first();
    }

    public static function findOrFail($id, $columns = ['*'])
    {
        $result = self::find($id, $columns);
        if ($result) {
            return $result;
        }

        throw new ModelNotFoundException();
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function delete()
    {
        Content::where('_id', $this->comment->content_id)->decrement('comments_count');
        $this->deleteNotifications();

        return parent::delete();
    }

    public function deleteNotifications()
    {
        Notification::where('comment_reply_id', $this->getKey())->delete();
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
        $column = '_replies.$.'.$column;

        $builder = Comment::where('_id', $this->comment->_id)
            ->where('_replies._id', $this->_id);
        $builder->push($column, $value, $unique);
    }

    public function mpull($column, $value = null)
    {
        $column = '_replies.$.'.$column;

        $builder = Comment::where('_id', $this->comment->_id)
            ->where('_replies._id', $this->_id);
        $builder->pull($column, $value);
    }

    public function increment($column, $amount = 1)
    {
        $column = '_replies.$.'.$column;

        $builder = Comment::where('_id', $this->comment->_id)
            ->where('_replies._id', $this->_id);
        $builder->increment($column, $amount);
    }

    public function decrement($column, $amount = 1)
    {
        $column = '_replies.$.'.$column;

        $builder = Comment::where('_id', $this->comment->_id)
            ->where('_replies._id', $this->_id);
        $builder->decrement($column, $amount);
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
        $url = route('content_comments', $this->comment->content_id);

        return  $url.'#'.$this->getKey();
    }

    public function canEdit()
    {
        return Auth::id() == $this->user_id
            && $this == $this->comment->replies->last();
    }

    public function canRemove()
    {
        return Auth::id() == $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
