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

/**
 * User model.
 *
 * @property string $_id
 * @property string $name User name
 * @property string $email User email address, hashed
 * @property string $password User password, hashed
 * @property DateTime $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|UserAction[] $actions 
 * @property-read \Illuminate\Database\Eloquent\Collection|Content[] $contents 
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $comments 
 * @property-read \Illuminate\Database\Eloquent\Collection|Entry[] $entries 
 * @property-read \Illuminate\Database\Eloquent\Collection|Folder[] $folders 
 * @property-read \Illuminate\Database\Eloquent\Collection|Notification[] $notifications 
 * @property-read \Illuminate\Database\Eloquent\Collection|UserSetting[] $settings 
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $bannedGroups 
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $blockedGroups 
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $subscribedGroups 
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $moderatedGroups 
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $blockedUsers 
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $followedUsers 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\User name($value)
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
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

    public function entries()
    {
        return $this->hasMany(Entry::class);
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
        return $this->belongsToMany(Group::class, 'group_bans');
    }

    public function blockedGroups()
    {
        return $this->belongsToMany(Group::class, 'user_blocked_groups');
    }

    public function subscribedGroups()
    {
        return $this->belongsToMany(Group::class, 'user_subscribed_groups');
    }

    public function moderatedGroups()
    {
        return $this->belongsToMany(Group::class, 'group_moderators')->withPivot('type');
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'user_blocked_users', 'source_id', 'target_id');
    }

    public function followedUsers()
    {
        return $this->belongsToMany(User::class, 'user_followed_users', 'source_id', 'target_id');
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

    public function isSubscriber(Group $group)
    {
        return $this->subscribedGroups()->where('group_id', $group)->exists();
    }

    public function isBlocking(Group $group)
    {
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
