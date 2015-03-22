<?php namespace Strimoid\Models;

use Auth;
use Strimoid\Helpers\MarkdownParser;

class Entry extends BaseModel
{
    protected static $rules = [
        'text'      => 'required|min:1|max:2500',
        'groupname' => 'required|exists:groups,urlname',
    ];

    protected $appends = ['vote_state'];
    protected $table = 'entries';
    protected $fillable = ['text'];
    protected $visible = ['_id', 'created_at', 'user', 'group', 'text', 'text_source',
        'uv', 'dv', 'votes', 'vote_state', 'replies', ];

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function replies()
    {
        return $this
            ->hasMany('Strimoid\Models\EntryReply', 'parent_id')
            ->with('User');
    }

    public function delete()
    {
        foreach ($this->replies as $reply) {
            $reply->delete();
        }

        Notification::where('entry_id', $this->getKey())->delete();

        return parent::delete();
    }

    public function deleteNotifications()
    {
        Notification::where('entry_id', $this->getKey())->delete();
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
