<?php namespace Strimoid\Models;

use App;
use Auth;
use Strimoid\Helpers\MarkdownParser;
use Strimoid\Models\Traits\HasAvatar;

/**
 * Group model.
 *
 * @property string $_id
 * @property string $name Group name
 * @property string $description Group description
 * @property string $avatar Group avatar filename
 * @property string $sidebar Sidebar text
 * @property DateTime $created_at
 */
class Group extends BaseModel
{
    use HasAvatar;

    protected $avatarPath = 'groups/';
    protected $attributes = [
        'subscribers' => 0,
        'type'        => self::TYPE_PUBLIC,
    ];
    protected $table = 'groups';
    protected $visible = [
        '_id', 'avatar', 'created_at', 'creator',
        'description', 'sidebar', 'subscribers', 'name',
    ];

    const TYPE_PUBLIC = 0;
    const TYPE_MODERATED = 1;
    const TYPE_PRIVATE = 2;
    const TYPE_ANNOUNCEMENTS  = 99;

    public function creator()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function entries()
    {
        $relation = $this->hasMany('Strimoid\Models\Entry');

        if (Auth::check()) {
            $blockedUsers = Auth::user()->blockedUsers();
            $relation->whereNotIn('user_id', (array) $blockedUsers);
        }

        return $relation;
    }

    public function comments($sortBy = null)
    {
        $relation = $this->hasMany('Strimoid\Models\Comment');
        $relation->orderBy($sortBy ?: 'created_at', 'desc');

        if (Auth::check()) {
            $blockedUsers = Auth::user()->blockedUsers();
            $relation->whereNotIn('user_id', (array) $blockedUsers);
        }

        return $relation;
    }

    public function contents($tab = null, $sortBy = null)
    {
        $relation = $this->hasMany('Strimoid\Models\Content');

        if (Auth::check()) {
            $blockedUsers = Auth::user()->blockedUsers();
            $relation->whereNotIn('user_id', $blockedUsers);

            $blockedDomains = Auth::user()->blocked_domains;
            $relation->whereNotIn('domain', $blockedDomains);
        }

        if ($tab == 'popular') {
            $threshold = $this->popular_threshold ?: 1;
            $relation->where('score', '>', $threshold);
        }

        $relation->orderBy($sortBy ?: 'created_at', 'desc');

        return $relation;
    }

    public function banned()
    {
        return $this->embedsMany('GroupBanned');
    }

    public function moderators()
    {
        return $this->embedsMany('GroupModerator');
    }

    public function checkAccess()
    {
        if ($this->type == Group::TYPE_PRIVATE) {
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
        if ($this->avatar) {
            return '/uploads/groups/'.$this->avatar;
        }

        return '/static/img/default_avatar.png';
    }

    public function setStyle($css)
    {
        $disk = Storage::disk('styles');

        // Compatibility with old saving method
        $filename = Str::lower($this->urlname).'.css';
        if ($disk->exists($filename)) {
            $disk->delete($filename);
        }

        $this->deleteStyle();

        if ($css) {
            $this->style = $this->shadow_urlname.'.'.Str::random(8).'.css';

            $disk->put($this->style, $css);
        }
    }

    public function deleteStyle()
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

        $ban = new GroupBanned();

        $ban->group()->associate($this);
        $ban->user()->associate($user);
        $ban->moderator()->associate(Auth::user());
        $ban->reason = Input::get('reason');

        $ban->save();
    }

    public function getAvatarPathAttribute()
    {
        $host = Config::get('app.cdn_host');

        if ($this->avatar) {
            return $host.'/groups/'.$this->avatar;
        }

        return $host.'/static/img/default_avatar.png';
    }

    public function setSidebarAttribute($text)
    {
        $this->attributes['sidebar'] = MarkdownParser::instance()->text(parse_usernames($text));
        $this->attributes['sidebar_source'] = $text;
    }

    public function setURLNameAttribute($text)
    {
        $this->attributes['urlname'] = $text;
        $this->attributes['shadow_urlname'] = shadow($text);
    }

    /* Scopes */

    public function scopeShadow($query, $name)
    {
        return $query->where('shadow_urlname', shadow($name));
    }
}
