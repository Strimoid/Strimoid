<?php

namespace Strimoid\Models;

use Auth;
use Strimoid\Facades\Markdown;
use Strimoid\Models\Traits\HasGroupRelationship;
use Strimoid\Models\Traits\HasNotificationsRelationship;
use Strimoid\Models\Traits\HasSaves;
use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\HasVotes;

class Comment extends BaseModel
{
    use HasGroupRelationship, HasUserRelationship, HasNotificationsRelationship;
    use HasSaves, HasVotes;

    protected static array $rules = [
        'text' => 'required|min:1|max:5000',
    ];

    protected $appends = ['vote_state'];
    protected $table = 'comments';
    protected $fillable = ['text'];
    protected $hidden = ['_replies', 'content_id', 'text_source', 'updated_at'];

    public static function boot(): void
    {
        static::creating(function ($comment): void {
            $comment->group_id = $comment->content->group_id;
        });

        static::created(function ($comment): void {
            $comment->content->increment('comments_count');
        });

        static::bootTraits();
    }

    public function content()
    {
        return $this->belongsTo(Content::class)->withTrashed();
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'parent_id')
            ->orderBy('created_at')
            ->with('user');
    }

    public function delete()
    {
        foreach ($this->replies as $reply) {
            $reply->delete();
        }

        Content::where('id', $this->content_id)->decrement('comments_count');

        return parent::delete();
    }

    public function setTextAttribute($text): void
    {
        $this->attributes['text'] = Markdown::convertToHtml(parse_usernames($text));
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
        return route('content_comments', $this->content) . '#' . $this->hashId();
    }
}
