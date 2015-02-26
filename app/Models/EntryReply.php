<?php namespace Strimoid\Models;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;
use Strimoid\Helpers\MarkdownParser;

class EntryReply extends BaseModel
{
    protected static $rules = [
        'text' => 'required|min:1|max:2500',
    ];

    protected $attributes = [
        'uv'    => 0,
        'dv'    => 0,
        'score' => 0,
    ];

    protected static $unguarded = true;
    protected $appends = ['vote_state'];
    protected $fillable = ['text'];
    protected $hidden = ['entry_id', 'updated_at'];

    public function __construct($attributes = [])
    {
        $this->{$this->getKeyName()} = Str::random(8);

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->parent()->increment('replies_count');
        });
    }

    public static function find($id, $columns = ['*'])
    {
        $parent = Entry::where('_replies._id', $id)
            ->project(['_replies' => ['$elemMatch' => ['_id' => $id]]])
            ->first(['created_at', 'group_id', 'user_id', 'text', 'uv', 'dv', 'votes']);

        if (! $parent) {
            return;
        }

        return $parent->replies->first();
    }

    public static function findOrFail($id, $columns = ['*'])
    {
        $result = self::find($id, $columns);
        if ($result) {
            return $result;
        }

        throw new ModelNotFoundException();
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function delete()
    {
        Entry::where('_id', $this->entry->_id)->decrement('replies_count');
        $this->deleteNotifications();

        return parent::delete();
    }

    public function deleteNotifications()
    {
        Notification::where('entry_reply_id', $this->_id)->delete();
    }

    public function getGroupIdAttribute($value)
    {
        return $this->entry->group_id;
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function mpush($column, $value = null, $unique = false)
    {
        $column = '_replies.$.'.$column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->push($column, $value, $unique);
    }

    public function mpull($column, $value = null)
    {
        $column = '_replies.$.'.$column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->pull($column, $value);
    }

    public function increment($column, $amount = 1)
    {
        $column = '_replies.$.'.$column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->increment($column, $amount);
    }

    public function decrement($column, $amount = 1)
    {
        $column = '_replies.$.'.$column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->decrement($column, $amount);
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
        $lastReply = Entry::where('_id', $this->entry->getKey())
            ->project(['_replies' => ['$slice' => -1]])
            ->first()->replies->first();

        return $lastReply->getKey() == $this->getKey();
    }

    public function getURL()
    {
        return route('single_entry', $this->entry->getKey())
            .'#'.$this->getKey();
    }

    public function canEdit()
    {
        return Auth::id() === $this->user_id
            && $this == $this->entry->replies->last();
    }

    public function canRemove()
    {
        return Auth::id() === $this->user_id
            || Auth::user()->isModerator($this->group_id);
    }
}
