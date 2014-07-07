<?php

use Helpers\Base58;

class Notification extends BaseModel
{

    protected $collection = 'notifications';
    protected $visible = ['_id', 'created_at', 'sourceUser', 'read', 'title', 'type', 'url'];

    public function sourceUser()
    {
        return $this->belongsTo('User')->select(['avatar']);
    }

    public function entry()
    {
        return $this->belongsTo('Entry');
    }

    public function entryReply()
    {
        return $this->belongsTo('EntryReply');
    }

    public function content()
    {
        return $this->belongsTo('Content');
    }

    public function comment()
    {
        return $this->belongsTo('Comment');
    }

    public function commentReply()
    {
        return $this->belongsTo('CommentReply');
    }

    public function conversation()
    {
        return $this->belongsTo('Conversation');
    }

    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function setTitle($title)
    {
        $clean = preg_replace('/<span class="spoiler">(.*?)<\/span>/s', '', $title);
        $clean = strip_tags($clean);
        $text = Str::limit($clean, 60);

        $this->title = $text;
    }

    public function setReadAttribute($value)
    {
        $this->attributes['users.read'] = toBool($value);
    }

    public function getURL($unread = false)
    {
        $url = null;
        $params = null;

        // Add parameter to mark notification as read
        if ($unread)
        {
            $params .= '?ntf_read='. Base58::encode($this->_id);
        }

        try {
            switch ($this->type)
            {
                case 'entry':
                    $url = URL::route('single_entry', $this->entry_id, false). $params;
                    break;
                case 'entry_reply':
                    $url = URL::route('single_entry_reply', $this->entry_reply_id, false). $params .'#'. $this->entry_reply_id;
                    break;
                case 'comment':
                    $url = URL::route('content_comments', $this->content_id, false). $params .'#'. $this->comment_id;
                    break;
                case 'comment_reply':
                    $url = URL::route('content_comments', $this->content_id, false). $params .'#'. $this->comment_reply_id;
                    break;
                case 'conversation':
                    $url = URL::route('conversation', $this->conversation_id, false). $params;
                    break;
                case 'moderator':
                    $url = URL::route('group_contents', $this->group_id, false). $params;
                    break;
            }
        } catch(Exception $e) {
            // Triggered when element was removed, but notification still exists
        }

        return $url;
    }

    public function getTypeDescription()
    {
        switch ($this->type)
        {
            case 'content':
                return 'Treść';
            case 'related':
                return 'Powiązany link';
            case 'entry':
                return 'Wpis';
            case 'entry_reply':
                return 'Odpowiedź na wpis';
            case 'comment':
                return 'Komentarz';
            case 'comment_reply':
                return 'Odpowiedź na komentarz';
            case 'conversation':
                return 'Konwersacja';
            case 'moderator':
                return 'Powiadomienie';
        }
    }

    public function getThumbnailPath()
    {
        return $this->sourceUser->getAvatarPath();
    }


}