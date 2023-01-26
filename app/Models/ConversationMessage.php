<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Strimoid\Facades\Markdown;

class ConversationMessage extends BaseModel
{
    protected $table = 'conversation_messages';
    protected $visible = [
        'id', 'conversation', 'created_at', 'user', 'text',
    ];

    protected static $unguarded = true;

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setTextAttribute($text): void
    {
        $this->attributes['text'] = Markdown::convertToHtml(parse_usernames($text))->getContent();
        $this->attributes['text_source'] = $text;
    }
}
