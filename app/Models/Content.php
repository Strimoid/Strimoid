<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use OEmbed;
use PDP;
use Str;
use Strimoid\Helpers\MarkdownParser;
use Strimoid\Models\Traits\HasGroupRelationship;
use Strimoid\Models\Traits\HasSaves;
use Strimoid\Models\Traits\HasThumbnail;
use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\HasVotes;

class Content extends BaseModel
{
    use HasGroupRelationship, HasThumbnail, HasUserRelationship, SoftDeletes;
    use HasSaves, HasVotes;

    protected static $rules = [
        'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
        'description' => 'max:255',
        'groupname' => 'required|exists:groups,urlname',
    ];

    protected $table = 'contents';
    protected $dates = ['deleted_at', 'frontpage_at'];
    protected $appends = ['hashid', 'vote_state'];
    protected $fillable = ['title', 'description', 'nsfw', 'eng', 'text', 'url'];
    protected $hidden = ['text', 'text_source', 'updated_at'];

    public function __construct($attributes = [])
    {
        static::deleted(function (Content $content) {
            Notification::where('content_id', $this->getKey())->delete();

            if (!$content->trashed()) {
                foreach ($this->comments() as $comment) {
                    $comment->delete();
                }
            }
        });

        parent::__construct($attributes);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function related()
    {
        return $this->hasMany(ContentRelated::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    public function getDomain()
    {
        return $this->domain ?: 'strimoid.pl';
    }

    public function getEmbed($autoPlay = true)
    {
        if (!$this->url) {
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
        $params = [$this, Str::slug($this->title)];

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
        $isAuthor = $user->getKey() == $this->user_id;
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
        $where = $exists ? 'whereNotNull' : 'whereNull';

        return $query->{ $where }('frontpage_at');
    }

    public function scopePopular($query)
    {
        return $query->where('score', '>', 1);
    }
}
