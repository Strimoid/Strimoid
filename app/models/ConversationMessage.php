<?php namespace Strimoid\Models;

class ConversationMessage extends BaseModel {

    protected $collection = 'conversation_messages';
    protected $visible = ['_id', 'conversation', 'created_at', 'user', 'text'];

    public function conversation()
    {
        return $this->belongsTo('Conversation');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text($text);
        $this->attributes['text_source'] = $text;
    }

}
