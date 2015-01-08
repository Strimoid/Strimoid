<?php namespace Strimoid\Models;

use Auth, Config, Str, Hash;
use duxet\Rethinkdb\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * User model
 *
 * @property string $_id
 * @property string $name User name
 * @property string $email User email address, hashed
 * @property string $password User password, hashed
 * @property DateTime $created_at
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;

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
        switch($this->type)
        {
            case 'admin':
                return '<span class="user_admin">'. $this->name .'</span>';
            case 'deleted':
                return '<span class="user_deleted">'. $this->name .'</span>';
            default:
                return '<span class="user_normal">'. $this->name .'</span>';
        }
    }

    public function getAvatarPath($width = null, $height = null)
    {
        $url = Config::get('app.cdn_host');

        // Show default avatar if user is blocked
        if (Auth::check() && Auth::user()->isBlockingUser($this))
        {
            return $url .'/static/img/default_avatar.png';
        }

        if ($this->avatar && $width && $height)
        {
            return $url .'/'. $width .'x'. $height .'/avatars/'. $this->avatar;
        }
        elseif ($this->avatar)
        {
            return $url .'/avatars/'. $this->avatar;
        }
        else
        {
            return $url .'/static/img/default_avatar.png';
        }
    }

    public function getBlockedDomainsAttribute($value)
    {
        $blockedDomains = $this->getAttributeFromArray('_blocked_domains');

        return (array) $blockedDomains;
    }

    public function getSexClass()
    {
        if ($this->sex && in_array($this->sex, ['male', 'female']))
        {
            return $this->sex;
        }
        else
        {
            return 'nosex';
        }
    }

    public function setEmailAttribute($value)
    {
        $lowercase = Str::lower($value);

        $this->attributes['email'] = hash_email($lowercase);

        $shadow = str_replace('.', '', $lowercase);
        $shadow = preg_replace('/\+(.)*@/', '@', $shadow);

        $this->attributes['shadow_email'] = hash_email($shadow);
    }

    public function setNewEmailAttribute($value)
    {
        $lowercase = Str::lower($value);

        $this->attributes['new_email'] = hash_email($lowercase);

        $shadow = str_replace('.', '', $lowercase);
        $shadow = preg_replace('/\+(.)*@/', '@', $shadow);

        $this->attributes['shadow_new_email'] = hash_email($shadow);
    }

    public function changeEmailHashes($email, $shadow)
    {
        $this->attributes['email'] = $email;
        $this->attributes['shadow_email'] = $shadow;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function bannedGroups()
    {
        $cacheKey = 'user.'. $this->_id . '.banned_groups';
        $groups = DB::table('group_bans')->where('user_id', $this->_id)->remember(60, $cacheKey)->lists('group_id');

        return (array) $groups;
    }

    public function blockedGroups()
    {
        $cacheKey = 'user.'. $this->_id . '.blocked_groups';
        $groups = DB::table('group_blocks')->where('user_id', $this->_id)->remember(60, $cacheKey)->lists('group_id');

        return (array) $groups;
    }

    public function blockedUsers()
    {
        $cacheKey = 'user.'. $this->_id . '.blocked_users';
        $users = DB::table('user_blocks')->where('user_id', $this->_id)->remember(60, $cacheKey)->lists('target_id');

        return (array) $users;
    }

    public function subscribedGroups()
    {
        $cacheKey = 'user.'. $this->_id . '.subscribed_groups';
        $groups = DB::table('group_subscribers')->where('user_id', $this->_id)->remember(60, $cacheKey)->lists('group_id');
        return (array) $groups;
    }

    public function moderatedGroups()
    {
        $cacheKey = 'user.'. $this->_id . '.moderated_groups';
        $groups =DB::table('group_moderators')->where('user_id', $this->_id)->remember(60, $cacheKey)->lists('group_id');

        return (array) $groups;
    }

    public function folders()
    {
        return $this->embedsMany('Folder', '_folders');
    }

    public function setAvatar($file)
    {
        $this->deleteAvatar();

        $filename = Str::random(8) .'.png';

        $img = Image::make($file);
        $img->fit(100, 100);
        $img->save(Config::get('app.uploads_path').'/avatars/'. $filename);

        $this->avatar = $filename;
    }

    public function deleteAvatar()
    {
        if ($this->avatar)
        {
            File::delete(Config::get('app.uploads_path').'/avatars/'. $this->avatar);

            $this->unset('avatar');
        }
    }

    public function isBanned(Group $group)
    {
        $isBanned = GroupBanned::where('group_id', $group->getKey())->where('user_id', $this->getKey())->first();

        return (bool) $isBanned;
    }

    public function isAdmin($group)
    {
        if ($group instanceof Group)
            $group = $group->_id;

        if (GroupModerator::where('group_id', $group)->where('user_id', $this->getKey())->where('type', 'admin')->first())
            return true;
        else
            return false;
    }

    public function isModerator($group)
    {
        if ($group instanceof Group)
            $group = $group->_id;

        return in_array($group, $this->moderatedGroups());
    }

    public function isSubscriber(Group $group)
    {
        if (GroupSubscriber::where('group_id', $group->getKey())->where('user_id', $this->getKey())->first())
            return true;
        else
            return false;
    }

    public function isBlocking(Group $group)
    {
        if (GroupBlock::where('group_id', $group->getKey())->where('user_id', $this->getKey())->first())
            return true;
        else
            return false;
    }

    public function isObservingUser($user)
    {
        if ($user instanceof User)
            $user = $user->_id;

        return in_array($user, (array) $this->_observed_users);
    }

    public function isBlockingUser($user)
    {
        if ($user instanceof User)
            $user = $user->_id;

        if (in_array($user, $this->blockedUsers()))
            return true;
        else
            return false;
    }

    public function entries()
    {
        return $this->hasMany('Strimoid\Models\Entry');
    }

    /* Scopes */

    public function scopeShadow($query, $name)
    {
        return $query->where('shadow_name', shadow($name));
    }

}