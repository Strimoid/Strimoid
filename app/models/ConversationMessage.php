<?php namespace Strimoid\Models;

use Strimoid\Helpers\MarkdownParser;

class ConversationMessage extends BaseModel {

    protected $table = 'conversation_messages';
    protected $visible = [
        'id', 'conversation', 'created_at', 'user', 'text'
    ];

    public function conversation()
    {
        return $this->belongsTo('Strimoid\Models\Conversation');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text($text);
        $this->attributes['text_source'] = $text;
    }

}
