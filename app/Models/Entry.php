<?php namespace Strimoid\Models;

use Auth;
use Strimoid\Helpers\MarkdownParser;
use Strimoid\Models\Traits\HasGroupRelationship;
use Strimoid\Models\Traits\HasNotificationsRelationship;
use Strimoid\Models\Traits\HasUserRelationship;

/**
 * Strimoid\Models\Entry
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|EntryReply[] $replies 
 * @property-write mixed $text 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @property-read Group $group 
 * @property-read User $user 
 * @property-read \Illuminate\Database\Eloquent\Collection|Notification[] $notifications 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class Entry extends BaseModel
{
    use HasGroupRelationship, HasUserRelationship, HasNotificationsRelationship;

    protected static $rules = [
        'text'      => 'required|min:1|max:2500',
        'groupname' => 'required|exists:groups,urlname',
    ];

    protected $appends = ['vote_state'];
    protected $table = 'entries';
    protected $fillable = ['text'];
    protected $visible = ['id', 'created_at', 'user', 'group', 'text', 'text_source',
        'uv', 'dv', 'votes', 'vote_state', 'replies', ];

    public function replies()
    {
        return $this->hasMany(EntryReply::class, 'parent_id');
    }

    public function delete()
    {
        foreach ($this->replies as $reply) {
            $reply->delete();
        }

        $this->notifications()->delete();

        return parent::delete();
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function isHidden()
    {
        if (Auth::guest()) return false;

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

    public function canEdit()
    {
        return Auth::id() === $this->user_id
            && $this->replies_count == 0;
    }

    public function canRemove()
    {
        return Auth::id() === $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
