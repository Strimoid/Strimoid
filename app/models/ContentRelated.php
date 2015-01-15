<?php namespace Strimoid\Models;

use Str;

class ContentRelated extends BaseModel
{

    protected static $rules = [
        'title' => 'required|min:1|max:128',
        'url' => 'required|url_custom',
    ];

    protected $attributes = [
        'uv' => 0,
        'dv' => 0,
        'score' => 0,
    ];

    protected $table = 'content_related';
    protected $hidden = ['content_id', 'user_id', 'updated_at'];
    protected $fillable = ['title', 'nsfw', 'eng', 'url'];

    function __construct($attributes = array())
    {
        $this->{$this->getKeyName()} = Str::random(9);

        parent::__construct($attributes);
    }

    public function content()
    {
        return $this->belongsTo('Strimoid\Models\Content');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function delete()
    {
        Content::where('_id', $this->content_id)->decrement('related_count');

        return parent::delete();
    }

    public function setNsfwAttribute($value)
    {
        $this->attributes['nsfw'] = toBool($value);
    }

    public function setEngAttribute($value)
    {
        $this->attributes['eng'] = toBool($value);
    }

    public function getURL()
    {
        if ($this->url)
            return $this->url;
        else
            return route('content_comments', $this->getKey());
    }

    public function getThumbnailPath()
    {
        if ($this->thumbnail)
            return '/uploads/thumbnails/'. $this->thumbnail;
        else
            return '';
    }

    public function setThumbnail($path)
    {
        if ($this->thumbnail)
            unlink(Config::get('app.uploads_path').'/thumbnails/'. $this->thumbnail);

        if (strpos($path, '//') === 0)
            $path = 'http:'. $path;

        $data = file_get_contents($path);
        $filename = Str::random(9) .'.png';

        $img = Image::make($data);
        $img->grab(80, 50);
        $img->save(Config::get('app.uploads_path').'/thumbnails/'. $filename);

        $this->thumbnail = $filename;
        $this->save();
    }

    public function removeThumbnail()
    {
        if($this->thumbnail)
        {
            unlink(Config::get('app.uploads_path').'/thumbnails/'. $this->thumbnail);
            $this->unset('thumbnail');
        }
    }
}