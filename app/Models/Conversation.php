<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Conversation extends BaseModel
{
    protected $table = 'conversations';
    protected $visible = ['id', 'created_at', 'users', 'lastMessage'];
    public function __construct(\Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Auth\AuthManager $authManager)
    {
        parent::__construct($guard);
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(ConversationMessage::class)->orderBy('created_at', 'desc');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class)->orderBy('created_at', 'desc');
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'element');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_users');
    }

    public function target(): User
    {
        return $this->users->filter(fn ($value) => $value->getKey() !== $this->authManager->id())->first();
    }

    public function scopeWithUser($query, $userName): void
    {
        $query->whereHas('users', function ($q) use ($userName): void {
            $q->where('user_id', $userName);
        });
    }
}
