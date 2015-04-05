<?php namespace Strimoid\Models;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;
use Strimoid\Helpers\MarkdownParser;
use Strimoid\Models\Traits\HasGroupRelationship;
use Strimoid\Models\Traits\HasNotificationsRelationship;
use Strimoid\Models\Traits\HasUserRelationship;

/**
 * Strimoid\Models\EntryReply
 *
 * @property-read Entry $parent 
 * @property-write mixed $text 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @property-read User $user 
 * @property-read Group $group 
 * @property-read \Illuminate\Database\Eloquent\Collection|Notification[] $notifications 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class EntryReply extends BaseModel
{
    use HasUserRelationship, HasGroupRelationship, HasNotificationsRelationship;

    protected static $rules = [
        'text' => 'required|min:1|max:2500',
    ];

    protected static $unguarded = true;
    protected $appends = ['vote_state'];
    protected $fillable = ['text'];
    protected $hidden = ['entry_id', 'updated_at'];
    protected $table = 'entry_replies';

    public static function boot()
    {
        static::creating(function ($reply) {
            $reply->group_id = $reply->parent->group_id;
        });

        static::created(function ($reply) {
            $reply->parent->increment('replies_count');
        });

        parent::boot();
    }

    public function parent()
    {
        return $this->belongsTo(Entry::class);
    }

    public function delete()
    {
        Entry::where('id', $this->parent_id)->decrement('replies_count');

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
        $lastReply = Entry::where('id', $this->parent->getKey())
            ->project(['_replies' => ['$slice' => -1]])
            ->first()->replies->first();

        return $lastReply->getKey() == $this->getKey();
    }

    public function getURL()
    {
        return route('single_entry', $this->parent).'#'.$this->hashId();
    }

    public function canEdit()
    {
        return Auth::id() === $this->user_id
            && $this == $this->parent->replies->last();
    }

    public function canRemove()
    {
        return Auth::id() === $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
