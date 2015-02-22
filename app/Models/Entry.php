<?php namespace Strimoid\Models;

use Auth, Str;
use Strimoid\Helpers\MarkdownParser;

class Entry extends BaseModel
{

    protected static $rules = [
        'text' => 'required|min:1|max:2500',
        'groupname' => 'required|exists_ci:groups,urlname'
    ];

    protected $attributes = [
        'uv' => 0,
        'dv' => 0,
        'score' => 0,
        'replies_count' => 0,
    ];

    protected $appends = ['vote_state'];
    protected $table = 'entries';
    protected $fillable = ['text'];
    protected $visible = ['_id', 'created_at', 'user', 'group', 'text', 'text_source',
        'uv', 'dv', 'votes', 'vote_state', 'replies'];

    function __construct($attributes = [])
    {
        $this->{$this->getKeyName()} = Str::random(6);

        parent::__construct($attributes);
    }

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
        return $this->embedsMany('EntryReply', '_replies')->with('User');
    }

    public function delete()
    {
        foreach($this->replies as $reply)
        {
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
        return route('single_entry', $this->getKey());
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