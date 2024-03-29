<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
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

    public function replies(): HasMany
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
        $this->attributes['text'] = Markdown::convertToHtml(parse_usernames($text))->getContent();
        $this->attributes['text_source'] = $text;
    }

    public function isHidden(): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->isBlockingUser($this->user);
    }

    public function isLast(): bool
    {
        return $this->replies_count === 0;
    }

    public function getURL(): string
    {
        return route('single_entry', $this);
    }
}
