<?php namespace Strimoid\Models;

use Auth;
use Str;
use Strimoid\Helpers\MarkdownParser;

/**
 * Comment model.
 *
 * @property string $_id
 * @property Content $content
 * @property string $text
 * @property string $text_source
 * @property User $user
 */
class Comment extends BaseModel
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
    protected $table = 'comments';
    protected $fillable = ['text'];
    protected $hidden = ['_replies', 'content_id', 'text_source', 'updated_at'];

    public function __construct($attributes = [])
    {
        $this->{$this->getKeyName()} = Str::random(6);
        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $comment->group_id = $comment->content->group_id;
        });

        static::created(function ($comment) {
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
        return $this->embedsMany('CommentReply', '_replies')
            ->with('user');
    }

    public function delete()
    {
        foreach ($this->replies as $reply) {
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
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->isBlockingUser($this->user);
    }

    public function getURL()
    {
        return route('content_comments', $this->content_id).'#'.$this->getKey();
    }

    public function canEdit()
    {
        return Auth::id() === $this->user_id
            && $this->replies()->count() == 0;
    }

    public function canRemove()
    {
        return Auth::id() === $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
