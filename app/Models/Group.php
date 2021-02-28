<?php

namespace Strimoid\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Strimoid\Facades\Markdown;
use Strimoid\Models\Traits\HasAvatar;

class Group extends BaseModel
{
    use HasAvatar;

    protected string $avatarPath = 'groups/';
    protected $attributes = [
        'type' => 'public',
    ];

    protected $casts = [
        'subscribers_count' => 'integer',
    ];

    protected $table = 'groups';
    protected $visible = [
        'id', 'avatar', 'created_at', 'creator',
        'description', 'sidebar', 'subscribers', 'name', 'urlname',
    ];
    public function __construct(\Illuminate\Contracts\Auth\Guard $guard, \Illuminate\Contracts\Auth\Guard $guard, \Illuminate\Contracts\Auth\Guard $guard, \Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Foundation\Application $application, private \Illuminate\Contracts\Config\Repository $configRepository, private \Illuminate\Filesystem\FilesystemManager $filesystemManager)
    {
        parent::__construct($guard);
        parent::__construct($guard);
        parent::__construct($guard);
        parent::__construct($guard);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function entries()
    {
        $relation = $this->hasMany(Entry::class);

        if ($this->authManager->check()) {
            $blockedUsers = $this->authManager->user()->blockedUsers()->pluck('id');
            $relation->whereNotIn('user_id', $blockedUsers);
        }

        return $relation;
    }

    public function comments($sortBy = null)
    {
        $relation = $this->hasMany(Comment::class);
        $relation->orderBy($sortBy ?: 'created_at', 'desc');

        if ($this->authManager->check()) {
            $blockedUsers = $this->authManager->user()->blockedUsers()->pluck('id');
            $relation->whereNotIn('user_id', $blockedUsers);
        }

        return $relation;
    }

    public function contents($tab = null, $sortBy = null)
    {
        $relation = $this->hasMany(Content::class);

        if ($this->authManager->check()) {
            $blockedUsers = $this->authManager->user()->blockedUsers()->pluck('id');
            $relation->whereNotIn('user_id', $blockedUsers);

            $blockedDomains = $this->authManager->user()->blockedDomains();
            $relation->whereNotIn('domain', $blockedDomains);
        }

        if ($tab === 'popular') {
            $threshold = $this->popular_threshold ?: 1;
            $relation->where('score', '>', $threshold);
        }

        $relation->orderBy($sortBy ?: 'created_at', 'desc');

        return $relation;
    }

    public function bannedUsers()
    {
        return $this->belongsToMany(User::class, 'group_bans');
    }

    public function moderators()
    {
        return $this->belongsToMany(User::class, 'group_moderators');
    }

    public function checkAccess(): void
    {
        if ($this->type === 'private') {
            if (!$this->authManager->check() || !$this->authManager->user()->isModerator($this)) {
                $this->application->abort(403, 'Access denied');
            }
        }
    }

    public function delete()
    {
        $this->deleteAvatar();
        $this->deleteStyle();

        return parent::delete();
    }

    public function getAvatarPath(int $width = null, int $height = null)
    {
        $host = $this->configRepository->get('app.cdn_host');

        if ($this->avatar && $width && $height) {
            return $host . '/' . $width . 'x' . $height . '/groups/' . $this->avatar;
        }

        if ($this->avatar) {
            return $host . '/groups/' . $this->avatar;
        }

        return '/static/img/default_avatar.png';
    }

    public function setStyle($css): void
    {
        $disk = $this->filesystemManager->disk('styles');

        // Compatibility with old saving method
        $filename = Str::lower($this->urlname) . '.css';
        if ($disk->exists($filename)) {
            $disk->delete($filename);
        }

        $this->deleteStyle();

        if ($css) {
            $this->style = $this->urlname . '.' . Str::random(8) . '.css';

            $disk->put($this->style, $css);
        }
    }

    public function deleteStyle(): void
    {
        if ($this->style) {
            $this->filesystemManager->disk('styles')->delete($this->style);
            $this->style = null;
        }
    }

    public function banUser(User $user, $reason = ''): void
    {
        if ($user->isBanned($this)) {
            return;
        }

        $ban = new GroupBan();

        $ban->group()->associate($this);
        $ban->user()->associate($user);
        $ban->moderator()->associate($this->authManager->user());
        $ban->reason = $reason;

        $ban->save();
    }

    public function getAvatarPathAttribute(): string
    {
        $host = $this->configRepository->get('app.cdn_host');

        if ($this->avatar) {
            return $host . '/groups/' . $this->avatar;
        }

        return $host . '/static/img/default_avatar.png';
    }

    public function setSidebarAttribute($text): void
    {
        $this->attributes['sidebar'] = Markdown::convertToHtml(parse_usernames($text));
        $this->attributes['sidebar_source'] = $text;
    }

    public function getRouteKey(): string
    {
        return $this->urlname;
    }

    public function scopeName($query, $name): void
    {
        $query->where('urlname', $name);
    }
}
