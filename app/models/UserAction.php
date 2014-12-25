<?php namespace Strimoid\Models;

class UserAction extends BaseModel
{

    protected $table = 'user_actions';

    const TYPE_CONTENT = 1;
    const TYPE_COMMENT = 2;
    const TYPE_COMMENT_REPLY = 3;
    const TYPE_ENTRY = 4;
    const TYPE_ENTRY_REPLY = 5;

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function content()
    {
        return $this->belongsTo('Content');
    }

    public function entry()
    {
        return $this->belongsTo('Entry');
    }

    public function entryReply()
    {
        return $this->belongsTo('EntryReply');
    }

    public function comment()
    {
        return $this->belongsTo('Comment');
    }

    public function commentReply()
    {
        return $this->belongsTo('CommentReply');
    }

    public function getObject()
    {
        switch($this->type)
        {
            case self::TYPE_CONTENT:        return $this->content;
            case self::TYPE_COMMENT:        return $this->comment;
            case self::TYPE_COMMENT_REPLY:  return CommentReply::find($this->comment_reply_id);
            case self::TYPE_ENTRY:          return $this->entry;
            case self::TYPE_ENTRY_REPLY:    return EntryReply::find($this->entry_reply_id);
        }

        throw new Exception('Invalid user action type');
    }

}
