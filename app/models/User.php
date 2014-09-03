<?php

use Jenssegers\Mongodb\Model as Eloquent;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    protected $collection = 'users';
    protected $visible = ['_id', 'age', 'avatar', 'created_at', 'description', 'location', 'sex'];
    protected $dates = ['last_login'];

    public static function boot()
    {
        parent::boot();

        User::created(function($user)
        {
            $data = new UserData();
            $data->_id = $user->_id;
            $data->save();
        });
    }

    public function data()
    {
        return $this->hasOne('UserData', '_id');
    }

    public function getReminderEmail()
    {
        return Str::lower($this->email);
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getColoredName()
    {
        /*if ($this->_id == 'zskk')
        {
            return '<span style="color: #BBACD5">'. $this->name .'</span>';
        }*/

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
        $url = Request::secure() ? '//strimoid.pl' : Config::get('app.cdn_host');

        // Show default avatar if user is blocked
        if (Auth::check() && Auth::user()->isBlockingUser($this))
        {
            return $url .'/static/img/default_avatar.png';
        }

        if ($this->avatar && $width && $height)
        {
            return $url .'/uploads/'. $width .'x'. $height .'/avatars/'. $this->avatar;
        }
        elseif ($this->avatar)
        {
            return $url .'/uploads/avatars/'. $this->avatar;
        }
        else
        {
            return $url .'/static/img/default_avatar.png';
        }
    }

    public function getSexClass()
    {
        if ($this->sex == 'male')
            return 'male';
        elseif ($this->sex == 'female')
            return 'female';
        else
            return 'nosex';
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

    public function blockedDomains()
    {
        return (array) array_key_exists('_blocked_domains', $this->attributes) ? $this->attributes['_blocked_domains'] : [];
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
        return $this->hasMany('Entry');
    }

    /* Scopes */

    public function scopeShadow($query, $name)
    {
        return $query->where('shadow_name', shadow($name));
    }

}