<?php namespace Strimoid\Models;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;
use Strimoid\Helpers\MarkdownParser;
use Strimoid\Models\Traits\HasGroupRelationship;
use Strimoid\Models\Traits\HasUserRelationship;

class CommentReply extends BaseModel
{
    use HasGroupRelationship, HasUserRelationship;

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
            $comment->content_id = $comment->parent->content_id;
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
        $url = route('content_comments', $this->parent->content);
        return $url.'#'.$this->hashId();
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
