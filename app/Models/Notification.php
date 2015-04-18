<?php namespace Strimoid\Models;

use Auth;
use Str;
use URL;

class Notification extends BaseModel
{
    protected $table = 'notifications';
    protected $visible = [
        'id', 'created_at', 'user',
        'read', 'title', 'type', 'url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['avatar', 'name']);
    }

    public function targets()
    {
        return $this->belongsToMany(User::class, 'notification_targets')->withPivot('read');
    }

    public function element()
    {
        return $this->morphTo();
    }

    public function setTitle($title)
    {
        $clean = preg_replace('/<span class="spoiler">(.*?)<\/span>/s', '', $title);
        $clean = strip_tags($clean);
        $text = Str::limit($clean, 60);

        $this->title = $text;
    }

    public function getReadAttribute()
    {
        $target = $this->targets->filter(function ($x) {
            return $x->user_id == Auth::id();
        })->first();

        if (! $target) {
            return false;
        }

        return $target->read;
    }

    public function setReadAttribute($value)
    {
        $this->attributes['users.read'] = toBool($value);
    }

    public function getURL()
    {
        $url = null;
        $params = null;

        // Add parameter to mark notification as read
        if (!$this->read) {
            $params .= '?ntf_read='.mid_to_b58($this->getKey());
        }

        try {
            switch ($this->type) {
                case 'entry':
                    $url = URL::route('single_entry', $this->entry_id, false)
                        .$params;
                    break;
                case 'entry_reply':
                    $url = URL::route('single_entry_reply', $this->entry_reply_id, false)
                        .$params.'#'.$this->entry_reply_id;
                    break;
                case 'comment':
                    $url = URL::route('content_comments', $this->content_id, false)
                        .$params.'#'.$this->comment_id;
                    break;
                case 'comment_reply':
                    $url = URL::route('content_comments', $this->content_id, false)
                        .$params.'#'.$this->comment_reply_id;
                    break;
                case 'conversation':
                    $url = URL::route('conversation', $this->conversation_id, false)
                        .$params;
                    break;
                case 'moderator':
                    $url = URL::route('group_contents', $this->group_id, false)
                        .$params;
                    break;
            }
        } catch (Exception $e) {
            // Triggered when element was removed, but notification still exists
        }

        return $url;
    }

    public function getTypeDescription()
    {
        switch ($this->type) {
            case 'content':           return 'Treść';
            case 'related':           return 'Powiązany link';
            case 'entry':             return 'Wpis';
            case 'entry_reply':       return 'Odpowiedź na wpis';
            case 'comment':           return 'Komentarz';
            case 'comment_reply':     return 'Odpowiedź na komentarz';
            case 'conversation':      return 'Konwersacja';
            case 'moderator':         return 'Powiadomienie';
        }
    }

    public function getThumbnailPath()
    {
        return $this->user->getAvatarPath();
    }

    public function addTarget(User $user)
    {
        $target = new NotificationTarget();
        $target->user()->associate($user);

        $this->targets()->save($target);
    }

    public function scopeTarget($query, $param)
    {
        $query->whereHas('targets', function($q) use($param)
        {
            $q->where('user_id', $param);
        });
    }
}
