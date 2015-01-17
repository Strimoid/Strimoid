<?php namespace Strimoid\Models;

use Auth;

class Conversation extends BaseModel {

    protected $table = 'conversations';
    protected $visible = ['id', 'created_at', 'users', 'lastMessage'];

    public function lastMessage()
    {
        return $this->hasOne('Strimoid\Models\ConversationMessage')
            ->orderBy('created_at', 'desc');
    }

    public function getUser()
    {
        foreach ($this->users as $user)
        {
            if ($user == Auth::id()) continue;

            return User::find($user);
        }
    }

    public function getLastMessage()
    {
        return ConversationMessage::where('conversation_id', $this->getKey())
            ->first();
    }

}