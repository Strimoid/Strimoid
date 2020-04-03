<?php

namespace Strimoid\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Strimoid\Models\Traits\HasAvatar;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasApiTokens, HasAvatar, Notifiable;

    protected string $avatarPath = 'avatars/';
    protected $dates = ['last_login'];
    protected $table = 'users';
    protected $fillable = [
        'age', 'description', 'location', 'sex',
    ];
    protected $visible = [
        'id', 'age', 'avatar', 'created_at',
        'description', 'location', 'sex', 'name',
    ];
    protected $casts = [
        'settings' => 'array',
    ];

    public function getColoredName(): string
    {
        $type = $this->type ?: 'normal';

        return '<span class="user_' . $type . '">' . $this->name . '</span>';
    }

    public function getAvatarPath(int $width = null, int $height = null)
    {
        $host = config('app.cdn_host');

        // Show default avatar if user is blocked
        if (Auth::check() && Auth::user()->isBlockingUser($this)) {
            return $this->getDefaultAvatarPath();
        }

        if ($this->avatar && $width && $height) {
            return $host . '/' . $width . 'x' . $height . '/avatars/' . $this->avatar;
        } elseif ($this->avatar) {
            return $host . '/avatars/' . $this->avatar;
        }

        return $this->getDefaultAvatarPath();
    }

    public function getDefaultAvatarPath(): string
    {
        $host = config('app.cdn_host');

        return $host . '/duck/' . $this->name . '.svg';
    }

    public function getSexClass(): string
    {
        if ($this->sex && in_array($this->sex, ['male', 'female'])) {
            return $this->sex;
        }

        return 'nosex';
    }

    public function setEmailAttribute($value): void
    {
        $lowercase = Str::lower($value);
        $this->attributes['email'] = $lowercase;
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function actions()
    {
        return $this->hasMany(UserAction::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentReplies()
    {
        return $this->hasMany(CommentReply::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function entryReplies()
    {
        return $this->hasMany(EntryReply::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_targets')->withPivot('read');
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }

    public function bannedGroups()
    {
        return $this->belongsToMany(Group::class, 'group_bans')->withTimestamps();
    }

    public function blockedGroups()
    {
        return $this->belongsToMany(Group::class, 'user_blocked_groups')->withTimestamps();
    }

    public function subscribedGroups()
    {
        return $this->belongsToMany(Group::class, 'user_subscribed_groups')->withTimestamps();
    }

    public function moderatedGroups()
    {
        return $this->belongsToMany(Group::class, 'group_moderators')->withTimestamps()->withPivot('type');
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(self::class, 'user_blocked_users', 'source_id', 'target_id')->withTimestamps();
    }

    public function followedUsers()
    {
        return $this->belongsToMany(self::class, 'user_followed_users', 'source_id', 'target_id')->withTimestamps();
    }

    public function blockedDomains(): object
    {
        return DB::table('user_blocked_domains')->where('user_id', $this->getKey())->pluck('domain');
    }

    public function isBanned(Group $group): bool
    {
        return $this->bannedGroups()->where('group_id', $group->id)->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isAdmin($group): bool
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        return $this->moderatedGroups()
            ->where('group_id', $group)
            ->where('group_moderators.type', 'admin')
            ->exists();
    }

    public function isModerator($group): bool
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        $cacheTags = ['users.moderated-groups', 'u.' . auth()->id()];
        $moderatedGroupsIds = $this->moderatedGroups()->remember(60)->cacheTags($cacheTags)->pluck('groups.id');

        return $moderatedGroupsIds->has($group);
    }

    public function isSubscriber($group): bool
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        $cacheTags = ['users.subscribed-groups', 'u.' . auth()->id()];
        $subscribedGroupsIds = $this->subscribedGroups()->remember(60)->cacheTags($cacheTags)->pluck('id');

        return $subscribedGroupsIds->has($group);
    }

    public function isBlocking($group): bool
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        $cacheTags = ['users.blocked-groups', 'u.' . auth()->id()];
        $blockedGroupsIds = $this->blockedGroups()->remember(60)->cacheTags($cacheTags)->pluck('id');

        return $blockedGroupsIds->has($group);
    }

    public function isObservingUser($user): bool
    {
        return false;
    }

    public function isBlockingUser($user): bool
    {
        if ($user instanceof self) {
            $user = $user->getKey();
        }

        $cacheTags = ['users.blocked-users', 'u.' . auth()->id()];
        $blockedUsersIds = $this->blockedUsers()->remember(60)->cacheTags($cacheTags)->pluck('id');

        return $blockedUsersIds->has($user);
    }

    public function getRouteKey(): string
    {
        return $this->name;
    }

    public function scopeName($query, $value): void
    {
        $query->where('name', 'ILIKE', $value);
    }
}
