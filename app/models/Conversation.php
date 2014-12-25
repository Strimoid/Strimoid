<?php

class Conversation extends BaseModel {

    protected $table = 'conversations';
    protected $visible = ['_id', 'created_at', 'users', 'lastMessage'];

    public function lastMessage()
    {
        return $this->hasOne('ConversationMessage')->orderBy('created_at', 'desc');
    }

    public function getUser()
    {
        foreach ($this->users as $user)
        {
            if ($user == Auth::user()->_id)
                continue;

            return User::find($user);
        }
    }

    public function getLastMessage()
    {
        return $message = ConversationMessage::where('conversation_id', $this->_id)
            ->first();
    }

}