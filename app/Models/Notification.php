<?php

namespace Strimoid\Models;

use Auth;
use Hashids;
use Str;
use Strimoid\Models\Traits\HasUserRelationship;

class Notification extends BaseModel
{
    use HasUserRelationship;

    protected $table = 'notifications';
    protected $visible = ['id', 'created_at', 'user', 'read', 'title', 'type', 'url'];

    public function targets()
    {
        return $this->belongsToMany(User::class, 'notification_targets')->withPivot('read');
    }

    public function element()
    {
        return $this->morphTo();
    }

    public function setTitle($title): void
    {
        $clean = preg_replace('/<span class="spoiler">(.*?)<\/span>/s', '', $title);
        $clean = strip_tags($clean);
        $text = Str::limit($clean, 60);

        $this->title = $text;
    }

    public function getReadAttribute()
    {
        if (!isset($this->pivot)) {
            return false;
        }

        return Auth::check() ? $this->pivot->read : false;
    }

    public function getURL()
    {
        $url = null;
        $params = null;

        // Add parameter to mark notification as read
        if (!$this->read) {
            $params .= '?ntf_read=' . $this->hashId();
        }

        if (!$this->element) {
            return null;
        }

        $class = get_class($this->element);

        switch ($class) {
            case Entry::class:
                $url = route('single_entry', $this->element);
                break;
            case EntryReply::class:
                $url = route('single_entry', Hashids::encode($this->element->parent_id));
                $params .= '#' . $this->element->hashId();
                break;
            case Comment::class:
                $url = route('content_comments', Hashids::encode($this->element->content_id));
                $params .= '#' . $this->element->hashId();
                break;
            case CommentReply::class:
                $url = route('content_comments', Hashids::encode($this->element->parent->content_id));
                $params .= '#' . $this->element->hashId();
                break;
            case Conversation::class:
                $url = route('conversation', $this->element);
                break;
            case 'moderator':
                $url = route('group_contents', $this->element);
                break;
        }

        return $url . $params;
    }

    public function getTypeDescription()
    {
        if (!$this->element) {
            return null;
        }

        $class = get_class($this->element);
        $class = class_basename($class);

        return trans('notifications.types.' . $class);
    }

    public function getThumbnailPath()
    {
        return $this->user->getAvatarPath();
    }

    public function scopeTarget($query, $param): void
    {
        $query->whereHas('targets', function ($q) use ($param): void {
            $q->where('user_id', $param);
        });
    }
}
