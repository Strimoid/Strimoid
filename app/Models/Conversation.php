<?php namespace Strimoid\Models;

class Conversation extends BaseModel
{
    protected $table = 'conversations';
    protected $visible = ['id', 'created_at', 'users', 'lastMessage'];

    public function lastMessage()
    {
        return $this->hasOne(ConversationMessage::class)
            ->orderBy('created_at', 'desc');
    }

    public function messages()
    {
        $this->hasMany(ConversationMessage::class);
    }

    public function notifications()
    {
        $this->hasMany(Notification::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_users');
    }

    public function scopeWithUser($query, $userName)
    {
        $query->whereHas('users', function($q) use($userName) {
            $q->where('user_id', $userName);
        });
    }
}
