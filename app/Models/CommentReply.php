<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\Traits\HasNotificationsRelationship;

class CommentReply extends Comment
{
    use HasNotificationsRelationship;

    protected static array $rules = [
        'text' => 'required|min:1|max:5000',
    ];

    protected $appends = ['vote_state'];
    protected $hidden = ['text_source', 'updated_at'];
    protected $fillable = ['text'];
    protected $table = 'comment_replies';

    public static function boot(): void
    {
        static::creating(function ($comment): void {
            $comment->group_id = $comment->parent->group_id;
        });

        static::created(function ($reply): void {
            $reply->parent->content->increment('comments_count');
        });

        static::deleted(function ($reply): void {
            $reply->parent->content->decrement('comments_count');
        });

        static::bootTraits();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function isLast(): bool
    {
        $lastId = $this->parent->replies()
            ->orderBy('created_at', 'desc')
            ->value('id');

        return $lastId === $this->getKey();
    }

    public function getURL(): string
    {
        $url = route('content_comments', $this->parent->content);

        return $url . '#' . $this->hashId();
    }
}
