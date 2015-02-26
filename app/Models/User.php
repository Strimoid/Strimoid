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
 */
class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, HasAvatar;

    protected $avatarPath = 'avatars/';
    protected $table = 'users';
    protected $visible = [
        'id', 'age', 'avatar', 'created_at',
        'description', 'location', 'sex', 'name',
    ];
    protected $dates = ['last_login'];

    public function getReminderEmail()
    {
        return Str::lower($this->email);
    }

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

    public function getBlockedDomainsAttribute($value)
    {
        $blockedDomains = $this->getAttributeFromArray('_blocked_domains');

        return (array) $blockedDomains;
    }

    public function getSexClass()
    {
        if ($this->sex && in_array($this->sex, ['male', 'female'])) {
            return $this->sex;
        }

        return 'nosex';
    }

    public function setNameAttribute($value)
    {
        $lowercase = Str::lower($value);

        $this->attributes['name'] = $value;
        $this->attributes['shadow_name'] = $lowercase;
    }

    public function setEmailAttribute($value)
    {
        $lowercase = Str::lower($value);
        $this->attributes['email'] = $lowercase;

        $shadow = shadow_email($value);
        $this->attributes['shadow_email'] = $shadow;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function contents()
    {
        return $this->hasMany('Strimoid\Models\Content');
    }

    public function comments()
    {
        return $this->hasMany('Strimoid\Models\Comment');
    }

    public function entries()
    {
        return $this->hasMany('Strimoid\Models\Entry');
    }

    public function bannedGroups()
    {
        $groups = DB::table('group_bans')
            ->where('user_id', $this->getKey())
            ->lists('group_id');

        return (array) $groups;
    }

    public function blockedGroups()
    {
        return $this->embedsMany('GroupBlock', 'subscribed_groups');
    }

    public function blockedUsers()
    {
        return $this->embedsMany('UserBlocked', 'subscribed_groups');
    }

    public function subscribedGroups()
    {
        return $this->embedsMany('GroupSubscriber', 'subscribed_groups');
    }

    public function moderatedGroups()
    {
        $groups = DB::table('group_moderators')
            ->where('user_id', $this->getKey())
            ->lists('group_id');

        return (array) $groups;
    }

    public function folders()
    {
        return $this->embedsMany('Folder', '_folders');
    }

    public function isBanned(Group $group)
    {
        $isBanned = GroupBanned::where('group_id', $group->getKey())
            ->where('user_id', $this->getKey())->first();

        return (bool) $isBanned;
    }

    public function isAdmin($group)
    {
        if ($group instanceof Group) {
            $group = $group->_id;
        }

        $isAdmin = GroupModerator::where('group_id', $group)
            ->where('user_id', $this->getKey())
            ->where('type', 'admin')->first();

        return (bool) $isAdmin;
    }

    public function isModerator($group)
    {
        if ($group instanceof Group) {
            $group = $group->_id;
        }

        return in_array($group, $this->moderatedGroups());
    }

    public function isSubscriber(Group $group)
    {
        $isSubscriber = GroupSubscriber::where('group_id', $group->getKey())
            ->where('user_id', $this->getKey())->first();

        return (bool) $isSubscriber;
    }

    public function isBlocking(Group $group)
    {
        $isBlocking = GroupBlock::where('group_id', $group->getKey())
            ->where('user_id', $this->getKey())->first();

        return (bool) $isBlocking;
    }

    public function isObservingUser($user)
    {
        if ($user instanceof User) {
            $user = $user->_id;
        }

        return in_array($user, (array) $this->_observed_users);
    }

    public function isBlockingUser($user)
    {
        if ($user instanceof User) {
            $user = $user->_id;
        }

        return in_array($user, $this->blockedUsers());
    }

    /* Scopes */

    public function scopeShadow($query, $name)
    {
        return $query->where('shadow_name', shadow($name));
    }
}
