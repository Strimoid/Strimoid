<?php

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

}
