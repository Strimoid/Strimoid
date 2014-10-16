<?php

class EntryReply extends BaseModel
{

    protected static $rules = [
        'text' => 'required|min:1|max:2500'
    ];

    protected $attributes = array(
        'uv' => 0,
        'dv' => 0,
        'score' => 0,
    );

    protected static $unguarded = true;
    protected $appends = ['vote_state'];
    protected $collection = null;
    protected $fillable = ['text'];
    protected $hidden = ['entry_id', 'text_source', 'updated_at'];

    function __construct($attributes = array())
    {
        $this->_id = Str::random(8);

        parent::__construct($attributes);
    }

    public static function find($id, $columns = array('*')) {
        $parent = Entry::where('_replies._id', $id)
            ->project(['_replies' => ['$elemMatch' => ['_id' => $id]]])
            ->first(['created_at', 'group_id', 'user_id', 'text', 'uv', 'dv', 'votes']);

        if (!$parent)
        {
            return false;
        }

        return $parent->replies->first();
    }

    public function user()
    {
        return $this->belongsTo('User');
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
        $parser = Parsedown::instance();

        $this->attributes['text'] = $parser->parse(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function mpush($column, $value = null, $unique = false)
    {
        if (!$this->_id)
            return new Exception('Tried to push on model without id');

        $column = '_replies.$.'. $column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->push($column, $value, $unique);
    }

    public function mpull($column, $value = null)
    {
        if (!$this->_id)
            return new Exception('Tried to pull on model without id');

        $column = '_replies.$.'. $column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->pull($column, $value);
    }

    public function increment($column, $amount = 1) {
        $column = '_replies.$.'. $column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->increment($column, $amount);
    }

    public function decrement($column, $amount = 1) {
        $column = '_replies.$.'. $column;

        $builder = Entry::where('_id', $this->entry->_id)->where('_replies._id', $this->_id);
        $builder->decrement($column, $amount);
    }

    public function isHidden()
    {
        if (Auth::guest())
        {
            return false;
        }

        return Auth::user()->isBlockingUser($this->user);
    }

    public function isLast()
    {
        $lastReply = Entry::where('_id', $this->entry->_id)
            ->project(['_replies' => ['$slice' => -1]])
            ->first()->replies->first();

        return $lastReply->_id == $this->_id;
    }

    public function getURL()
    {
        return route('single_entry', $this->entry->_id) .'#'. $this->_id;
    }

    public function canEdit(User $user)
    {
        return Auth::user()->_id == $this->user_id && $this == $this->entry->replies->last();
    }

    public function canRemove(User $user)
    {
        return Auth::user()->_id == $this->user_id || Auth::user()->isModerator($this->group_id);
    }

}