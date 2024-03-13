<?php

namespace Strimoid\Models;

use Hashids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Strimoid\Models\Traits\HasUserRelationship;

class Notification extends BaseModel
{
    use HasUserRelationship;

    protected $table = 'notifications';

    protected $appends = ['thumbnail_path', 'type', 'url'];
    protected $visible = ['id', 'created_at', 'user', 'read', 'title', 'thumbnail_path', 'type', 'url'];

    public function targets(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_targets')->withPivot('read');
    }

    public function element(): MorphTo
    {
        return $this->morphTo();
    }

    public function setTitle($title): void
    {
        $clean = preg_replace('/<span class="spoiler">(.*?)<\/span>/s', '', (string) $title);
        $clean = strip_tags((string) $clean);
        $text = Str::limit($clean, 60);

        $this->title = $text;
    }

    public function getReadAttribute(): bool
    {
        if (!isset($this->pivot)) {
            return false;
        }

        return Auth::check() ? $this->pivot->read : false;
    }

    public function getUrlAttribute(): ?string
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

        $class = $this->element::class;

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

    public function getTypeAttribute(): ?string
    {
        if (!$this->element) {
            return null;
        }

        $class = class_basename($this->element);

        return trans('notifications.types.' . $class);
    }

    public function getThumbnailPathAttribute(): string
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
