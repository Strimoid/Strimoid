<?php namespace Strimoid\Models;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use OEmbed;
use PDP;
use Str;
use Strimoid\Helpers\MarkdownParser;
use Strimoid\Models\Traits\HasThumbnail;

/**
 * Content model.
 *
 * @property string   $_id          Content ID
 * @property string   $title        Content title
 * @property string   $description  Content description
 * @property bool     $eng          Is content using foreign language?
 * @property bool     $nsfw         Is Content "not safe for work"?
 * @property string   $thumbnail    Thumbnail filename
 * @property string   $domain       Domain
 * @property string   $url          URL address
 * @property DateTime $created_at   Date of creation
 */
class Content extends BaseModel
{
    use HasThumbnail, SoftDeletes;

    protected static $rules = [
        'title'       => 'required|min:1|max:128|not_in:edit,thumbnail',
        'description' => 'max:255',
        'groupname'   => 'required|exists_ci:groups,urlname',
    ];

    protected $attributes = [
        'uv'             => 0,
        'dv'             => 0,
        'score'          => 0,
        'comments_count' => 0,
    ];

    protected $table = 'contents';
    protected $dates = ['deleted_at', 'frontpage_at'];
    protected $appends = ['vote_state'];
    protected $fillable = ['title', 'description', 'nsfw', 'eng', 'text', 'url'];
    protected $hidden = ['text', 'text_source', 'updated_at'];

    public function __construct($attributes = [])
    {
        $this->{$this->getKeyName()} = Str::random(6);

        static::deleted(function (Content $content) {
            Notification::where('content_id', $this->getKey())->delete();

            if (! $content->trashed()) {
                foreach ($this->getComments() as $comment) {
                    $comment->delete();
                }
            }
        });

        parent::__construct($attributes);
    }

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User')
            ->select(['avatar', 'name']);
    }

    public function deleted_by()
    {
        return $this->belongsTo('Strimoid\Models\User', 'deleted_by');
    }

    public function related()
    {
        return $this->hasMany('Strimoid\Models\ContentRelated');
    }

    public function comments()
    {
        return $this->hasMany('Strimoid\Models\Comment')
            ->orderBy('created_at', 'asc');
    }

    public function getDomain()
    {
        return $this->domain ?: 'strimoid.pl';
    }

    public function getEmbed($autoPlay = true)
    {
        if (! $this->url) {
            return false;
        }

        return OEmbed::getEmbedHtml($this->url, $autoPlay);
    }

    public function getURL()
    {
        return $this->url ?: $this->getSlug();
    }

    public function getSlug()
    {
        $params = [$this->_id, Str::slug($this->title)];

        return route('content_comments_slug', $params);
    }

    public function setNsfwAttribute($value)
    {
        $this->attributes['nsfw'] = toBool($value);
    }

    public function setEngAttribute($value)
    {
        $this->attributes['eng'] = toBool($value);
    }

    public function setUrlAttribute($url)
    {
        $domain = PDP::parseUrl($url)->host->registerableDomain;
        $this->attributes['url'] = $url;
        $this->attributes['domain'] = $domain;
    }

    public function setTextAttribute($text)
    {
        $parser = MarkdownParser::instance();
        $parser->config('inline_images', true);
        $parser->config('headers', true);

        $this->attributes['text'] = $parser->text(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public static function validate($input)
    {
        $validator = Validator::make($input, static::$rules);

        $validator->sometimes('text', 'required|min:1|max:50000', function ($input) {
            return $input->text;
        });

        $validator->sometimes('url', 'required|url|safe_url|max:2048', function ($input) {
            return !$input->text;
        });

        return $validator;
    }

    /* Permissions */

    public function canEdit(User $user = null)
    {
        $isAuthor = $user->_id == $this->user_id;
        $hasTime = $this->created_at->diffInMinutes() < 30;

        $isAdmin = $user->type == 'admin';

        return ($isAuthor && $hasTime) || $isAdmin;
    }

    public function canRemove(User $user = null)
    {
        return $user->isModerator($this->group);
    }

    /* Scopes */

    public function scopeFrontpage($query, $exists = true)
    {
        return $query->where('frontpage_at', 'exists', $exists);
    }

    public function scopePopular($query)
    {
        return $query->where('score', '>', 1);
    }
}
