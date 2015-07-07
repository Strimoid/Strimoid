<?php namespace Strimoid\Models;

use Auth;
use Config;
use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Str;
use Strimoid\Models\Traits\HasAvatar;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, HasAvatar;

    protected $avatarPath  = 'avatars/';
    protected $dates       = ['last_login'];
    protected $table       = 'users';
    protected $visible     = [
        'id', 'age', 'avatar', 'created_at',
        'description', 'location', 'sex', 'name',
    ];

    public function getColoredName()
    {
        $type = $this->type ?: 'normal';

        return '<span class="user_'.$type.'">'.$this->name.'</span>';
    }

    public function getAvatarPath($width = null, $height = null)
    {
        $host = Config::get('app.cdn_host');

        // Show default avatar if user is blocked
        if (Auth::check() && Auth::user()->isBlockingUser($this)) {
            return $this->getDefaultAvatarPath();
        }

        if ($this->avatar && $width && $height) {
            return $host.'/'.$width.'x'.$height.'/avatars/'.$this->avatar;
        } elseif ($this->avatar) {
            return $host.'/avatars/'.$this->avatar;
        }

        return $this->getDefaultAvatarPath();
    }

    public function getDefaultAvatarPath()
    {
        $host = Config::get('app.cdn_host');

        return $host.'/duck/'.$this->name.'.svg';
    }

    public function getSexClass()
    {
        if ($this->sex && in_array($this->sex, ['male', 'female'])) {
            return $this->sex;
        }

        return 'nosex';
    }

    public function setEmailAttribute($value)
    {
        $lowercase = Str::lower($value);
        $this->attributes['email'] = $lowercase;

        $shadow = shadow_email($value);
        // TODO:
        //$this->attributes['shadow_email'] = $shadow;
    }

    public function setPasswordAttribute($value)
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
        return $this->belongsToMany(User::class, 'user_blocked_users', 'source_id', 'target_id')->withTimestamps();
    }

    public function followedUsers()
    {
        return $this->belongsToMany(User::class, 'user_followed_users', 'source_id', 'target_id')->withTimestamps();
    }

    public function blockedDomains()
    {
        return DB::table('user_blocked_domains')->where('user_id', $this->getKey())->lists('domain');
    }

    public function isBanned(Group $group)
    {
        return $this->bannedGroups()->where('group_id', $group)->exists();
    }

    public function isAdmin($group)
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        return $this->moderatedGroups()
            ->where('group_id', $group)
            ->where('group_moderators.type', 'admin')
            ->exists();
    }

    public function isModerator($group)
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        return $this->moderatedGroups()->where('group_id', $group)->exists();
    }

    public function isSubscriber($group)
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        return $this->subscribedGroups()->where('group_id', $group)->exists();
    }

    public function isBlocking($group)
    {
        if ($group instanceof Group) {
            $group = $group->getKey();
        }

        return $this->blockedGroups()->where('group_id', $group)->exists();
    }

    public function isObservingUser($user)
    {
        return false;
        //return $this->subscribedGroups()->where('group_id', $group)->exists();
    }

    public function isBlockingUser($user)
    {
        if ($user instanceof User) {
            $user = $user->getKey();
        }

        return $this->blockedUsers()->where('target_id', $user)->exists();
    }

    /**
     * Get the value of the model's route key.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return $this->name;
    }

    public function scopeName($query, $value)
    {
        $query->where('name', $value);
    }
}
