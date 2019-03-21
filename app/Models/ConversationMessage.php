<?php

namespace Strimoid\Models;

use Strimoid\Helpers\MarkdownParser;

class ConversationMessage extends BaseModel
{
    protected $table = 'conversation_messages';
    protected $visible = [
        'id', 'conversation', 'created_at', 'user', 'text',
    ];

    protected static $unguarded = true;

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text($text);
        $this->attributes['text_source'] = $text;
    }
}
