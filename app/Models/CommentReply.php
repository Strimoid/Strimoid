<?php namespace Strimoid\Models;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;
use Strimoid\Helpers\MarkdownParser;

class CommentReply extends BaseModel
{
    protected static $rules = [
        'text' => 'required|min:1|max:5000',
    ];

    protected $appends = ['vote_state'];
    protected $hidden = ['text_source', 'updated_at'];
    protected $fillable = ['text'];
    protected $table = 'comments';

    public static function boot()
    {
        static::creating(function ($comment) {
            $comment->content_id = $comment->parent->content_id;
            $comment->group_id = $comment->parent->group_id;
        });

        static::created(function ($reply) {
            $reply->parent->content->increment('comments_count');
        });

        parent::boot();
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function parent()
    {
        return $this->belongsTo('Strimoid\Models\Comment');
    }

    public function delete()
    {
        Content::where('_id', $this->comment->content_id)->decrement('comments_count');

        return parent::delete();
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
