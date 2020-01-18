<?php

namespace Strimoid\Models;

use Illuminate\Support\Facades\Auth;

class Conversation extends BaseModel
{
    protected $table = 'conversations';
    protected $visible = ['id', 'created_at', 'users', 'lastMessage'];

    public function lastMessage()
    {
        return $this->hasOne(ConversationMessage::class)->orderBy('created_at', 'desc');
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class)->orderBy('created_at', 'desc');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'element');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_users');
    }

    public function target()
    {
        return $this->users->filter(fn($value) => $value->getKey() != Auth::id())->first();
    }

    public function scopeWithUser($query, $userName): void
    {
        $query->whereHas('users', function ($q) use ($userName): void {
            $q->where('user_id', $userName);
        });
    }
}
