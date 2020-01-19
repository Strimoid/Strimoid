<?php

namespace Strimoid\Models;

use App;
use Auth;
use Strimoid\Helpers\MarkdownParser;
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

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function entries()
    {
        $relation = $this->hasMany(Entry::class);

        if (Auth::check()) {
            $blockedUsers = Auth::user()->blockedUsers()->pluck('id');
            $relation->whereNotIn('user_id', $blockedUsers);
        }

        return $relation;
    }

    public function comments($sortBy = null)
    {
        $relation = $this->hasMany(Comment::class);
        $relation->orderBy($sortBy ?: 'created_at', 'desc');

        if (Auth::check()) {
            $blockedUsers = Auth::user()->blockedUsers()->pluck('id');
            $relation->whereNotIn('user_id', $blockedUsers);
        }

        return $relation;
    }

    public function contents($tab = null, $sortBy = null)
    {
        $relation = $this->hasMany(Content::class);

        if (Auth::check()) {
            $blockedUsers = Auth::user()->blockedUsers()->pluck('id');
            $relation->whereNotIn('user_id', $blockedUsers);

            $blockedDomains = Auth::user()->blockedDomains();
            $relation->whereNotIn('domain', $blockedDomains);
        }

        if ($tab == 'popular') {
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
        if ($this->type == 'private') {
            if (!Auth::check() || !Auth::user()->isModerator($this)) {
                App::abort(403, 'Access denied');
            }
        }
    }

    public function delete()
    {
        $this->deleteAvatar();
        $this->deleteStyle();

        return parent::delete();
    }

    public function getAvatarPath()
    {
        $host = config('app.cdn_host');

        if ($this->avatar) {
            return $host . '/groups/' . $this->avatar;
        }

        return '/static/img/default_avatar.png';
    }

    public function setStyle($css): void
    {
        $disk = Storage::disk('styles');

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
            Storage::disk('styles')->delete($this->style);
            $this->unset('style');
        }
    }

    public function banUser(User $user, $reason = '')
    {
        if ($user->isBanned($this)) {
            return false;
        }

        $ban = new GroupBan();

        $ban->group()->associate($this);
        $ban->user()->associate($user);
        $ban->moderator()->associate(Auth::user());
        $ban->reason = $reason;

        $ban->save();
    }

    public function getAvatarPathAttribute()
    {
        $host = config('app.cdn_host');

        if ($this->avatar) {
            return $host . '/groups/' . $this->avatar;
        }

        return $host . '/static/img/default_avatar.png';
    }

    public function setSidebarAttribute($text): void
    {
        $this->attributes['sidebar'] = MarkdownParser::instance()->text(parse_usernames($text));
        $this->attributes['sidebar_source'] = $text;
    }

    /**
     * Get the value of the model's route key.
     *
     */
    public function getRouteKey(): string
    {
        return $this->urlname;
    }

    public function scopeName($query, $name): void
    {
        $query->where('urlname', $name);
    }
}
