<?php namespace Strimoid\Models;

class UserAction extends BaseModel
{
    protected $table = 'user_actions';
    protected static $unguarded = true;

    const TYPE_CONTENT = 1;
    const TYPE_COMMENT = 2;
    const TYPE_COMMENT_REPLY = 3;
    const TYPE_ENTRY = 4;
    const TYPE_ENTRY_REPLY = 5;

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function content()
    {
        return $this->belongsTo('Strimoid\Models\Content');
    }

    public function entry()
    {
        return $this->belongsTo('Strimoid\Models\Entry');
    }

    public function entryReply()
    {
        return $this->belongsTo('Strimoid\Models\EntryReply');
    }

    public function comment()
    {
        return $this->belongsTo('Strimoid\Models\Comment');
    }

    public function commentReply()
    {
        return $this->belongsTo('Strimoid\Models\CommentReply');
    }

    public function getObject()
    {
        switch ($this->type) {
            case self::TYPE_CONTENT:        return $this->content;
            case self::TYPE_COMMENT:        return $this->comment;
            case self::TYPE_COMMENT_REPLY:  return CommentReply::find($this->comment_reply_id);
            case self::TYPE_ENTRY:          return $this->entry;
            case self::TYPE_ENTRY_REPLY:    return EntryReply::find($this->entry_reply_id);
        }

        throw new Exception('Invalid user action type');
    }
}
