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
    public function __construct(\Illuminate\Contracts\Auth\Guard $guard, \Illuminate\Contracts\Auth\Guard $guard, \Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Routing\UrlGenerator $urlGenerator, private \Illuminate\Contracts\Auth\Guard $guard)
    {
        parent::__construct($guard);
        parent::__construct($guard);
        parent::__construct($guard);
    }

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
        $this->attributes['text'] = Markdown::convertToHtml(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function isHidden()
    {
        if ($this->authManager->guest()) {
            return false;
        }

        return $this->authManager->user()->isBlockingUser($this->user);
    }

    public function isLast()
    {
        return $this->replies_count === 0;
    }

    public function getURL()
    {
        return $this->urlGenerator->route('single_entry', $this);
    }

    public function isAuthor(User $user = null)
    {
        $userId = $user ? $user->getKey() : $this->guard->id();

        return (int) $userId === (int) $this->user_id;
    }

    public function canEdit()
    {
        return $this->isAuthor() && $this->replies_count === 0;
    }
}
