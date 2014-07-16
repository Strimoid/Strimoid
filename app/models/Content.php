<?php

use Summon\Summon;
use Jenssegers\Mongodb\Eloquent\SoftDeletingTrait;

class Content extends BaseModel
{

    use SoftDeletingTrait;

    protected static $rules = [
        'title' => 'required|min:1|max:128|not_in:edit,thumbnail',
        'description' => 'max:255',
        'groupname' => 'required|exists_ci:groups,urlname'
    ];

    protected $attributes = [
        'uv' => 0,
        'dv' => 0,
        'score' => 0,
        'comments' => 0,
    ];

    protected $collection = 'contents';
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'description', 'nsfw', 'eng', 'text', 'url'];
    protected $hidden = ['text', 'text_source', 'updated_at'];


    function __construct($attributes = array())
    {
        $this->_id = Str::random(6);

        static::deleted(function($content)
        {
            Notification::where('content_id', $this->getKey())->delete();

            if (!$content->trashed())
            {
                foreach ($this->getComments() as $comment)
                {
                    $comment->delete();
                }
            }
        });

        parent::__construct($attributes);
    }

    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function user()
    {
        return $this->belongsTo('User')->select(['avatar', 'name']);
    }

    public function deleted_by()
    {
        return $this->belongsTo('User', 'deleted_by');
    }

    public function related()
    {
        return $this->hasMany('ContentRelated');
    }

    public function comments()
    {
        return $this->hasMany('Comment');
    }

    public function getDomain()
    {
        if ($this->domain)
        {
            return $this->domain;
        }

        /*
        $pieces = parse_url($this->getURL());
        $domain = isset($pieces['host']) ? $pieces['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs))
        {
            return $regs['domain'];
        }
        */

        return 'strimoid.pl';
    }

    public function getEmbed()
    {
        if (!$this->embed)
        {
            $this->embed = Embed::make($this->getURL())->parseUrl();
        }

        return $this->embed;
    }

    public function getComments()
    {
        return Comment::with('user', 'replies.user')
            ->orderBy('created_at', 'asc')
            ->where('content_id', $this->getKey())
            ->get();
    }

    public function getRelated()
    {
        return ContentRelated::with('user')
            ->orderBy('created_at', 'asc')
            ->where('content_id', $this->getKey())
            ->get();
    }

    public function getURL()
    {
        if ($this->url)
        {
            return $this->url;
        }
        else
        {
            return route('content_comments_slug', [$this->_id, Str::slug($this->title)]);
        }
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
        $this->attributes['url'] = $url;
        $this->attributes['domain'] = PDP::parseUrl($url)->host->registerableDomain;
    }

    public function setTextAttribute($text)
    {
        $parser = Parsedown::instance();
        $parser->config('inline_images', true);
        $parser->config('headers', true);

        $this->attributes['text'] = $parser->parse(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function getThumbnailPath($width = null, $height = null)
    {
        $url = Request::secure() ? '//strimoid.pl' : '//static.strimoid.pl';

        if ($this->thumbnail && $width && $height)
        {
            return $url .'/uploads/'. $width .'x'. $height .'/thumbnails/'. $this->thumbnail;
        }
        elseif ($this->thumbnail)
        {
            return $url .'/uploads/thumbnails/'. $this->thumbnail;
        }

        return '';
    }

    public function autoThumbnail()
    {
        try {
            $summon = new Summon($this->getURL());
            $thumbnails = $summon->fetch();

            $this->setThumbnail($thumbnails['thumbnails'][0]);
        } catch(Exception $e){
        }
    }

    public function setThumbnail($path)
    {
        if ($this->thumbnail)
        {
            File::delete(Config::get('app.uploads_path').'/thumbnails/'. $this->thumbnail);
        }

        if (starts_with($path, '//'))
        {
            $path = 'http:'. $path;
        }

        $data = file_get_contents($path);
        $filename = Str::random(9) .'.png';

        $img = Image::make($data);
        $img->fit(400, 300);
        $img->save(Config::get('app.uploads_path').'/thumbnails/'. $filename);

        $this->thumbnail = $filename;
        $this->save();
    }

    public function removeThumbnail()
    {
        if ($this->thumbnail)
        {
            File::delete(Config::get('app.uploads_path').'/thumbnails/'. $this->thumbnail);
            $this->unset('thumbnail');
        }
    }

    public function isSaved()
    {
        return in_array($this->_id, (array) Auth::user()->data->_saved_contents);
    }

    public static function validate($input)
    {
        $validator = Validator::make($input, static::$rules);

        $validator->sometimes('text', 'required|min:1|max:50000', function($input)
        {
            return $input->text;
        });

        $validator->sometimes('url', 'required|url|safe_url|max:2048', function($input)
        {
            return !$input->text;
        });

        return $validator;
    }

    public function canEdit(User $user = null)
    {
        $isAuthor = $user->_id == $this->user_id;
        $hasTime = Carbon::instance($this->created_at)->diffInMinutes() < 30;

        $isAdmin = $user->type == 'admin';

        return ($isAuthor && $hasTime) || $isAdmin;
    }

    /* Scopes */

    public function scopeFrontpage($query, $exists = true)
    {
        return $query->where('frontpage_at', 'exists', $exists);
    }

}