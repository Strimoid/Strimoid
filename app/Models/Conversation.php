<?php namespace Strimoid\Models;

use Illuminate\Support\Facades\Auth;

/**
 * Strimoid\Models\Conversation
 *
 * @property-read ConversationMessage::class)->orderBy('crea $lastMessage 
 * @property-read \Illuminate\Database\Eloquent\Collection|ConversationMessage::class)->orderBy('crea[] $messages 
 * @property-read \Illuminate\Database\Eloquent\Collection|Notification[] $notifications 
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\Conversation withUser($userName)
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
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
        return $this->users->filter(function($value) {
                return $value->getKey() != Auth::id();
            })->first();
    }

    public function scopeWithUser($query, $userName)
    {
        $query->whereHas('users', function($q) use($userName) {
            $q->where('user_id', $userName);
        });
    }
}
