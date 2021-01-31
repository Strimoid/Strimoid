<?php

namespace Strimoid\Models;

use Auth;
use Strimoid\Facades\Markdown;
use Strimoid\Models\Traits\HasGroupRelationship;
use Strimoid\Models\Traits\HasNotificationsRelationship;
use Strimoid\Models\Traits\HasSaves;
use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\HasVotes;

class Entry extends BaseModel
{
    use HasGroupRelationship, HasUserRelationship, HasNotificationsRelationship;
    use HasSaves, HasVotes;

    protected static array $rules = [
        'text' => 'required|min:1|max:2500',
        'groupname' => 'required|exists:groups,urlname',
    ];

    protected $appends = ['vote_state'];
    protected $table = 'entries';
    protected $fillable = ['text'];
    protected $visible = ['id', 'created_at', 'user', 'group', 'text', 'text_source',
        'uv', 'dv', 'votes', 'vote_state', 'replies', ];

    public function replies()
    {
        return $this->hasMany(EntryReply::class, 'parent_id')->orderBy('created_at');
    }

    public function delete()
    {
        foreach ($this->replies as $reply) {
            $reply->delete();
        }

        $this->notifications()->delete();

        return parent::delete();
    }

    public function setTextAttribute($text): void
    {
        $this->attributes['text'] = Markdown::convertToHtml(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function isHidden()
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->isBlockingUser($this->user);
    }

    public function isLast()
    {
        return $this->replies_count == 0;
    }

    public function getURL()
    {
        return route('single_entry', $this);
    }

    public function isAuthor(User $user = null)
    {
        $userId = $user ? $user->getKey() : auth()->id();

        return (int) $userId === (int) $this->user_id;
    }

    public function canEdit()
    {
        return $this->isAuthor() && $this->replies_count == 0;
    }
}
