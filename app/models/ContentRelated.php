<?php

use Jenssegers\Mongodb\Model as Eloquent;

class ContentRelated extends BaseModel
{

    protected $attributes = array(
        'uv' => 0,
        'dv' => 0,
        'score' => 0,
    );

    protected $collection = 'content_related';
    protected $hidden = array('_id', 'content_id', 'user_id', 'updated_at');

    function __construct($attributes = array())
    {
        $this->_id = Str::random(9);

        parent::__construct($attributes);
    }

    public function content()
    {
        return $this->belongsTo('Content');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function delete()
    {
        Content::where('_id', $this->content_id)->decrement('related_count');

        return parent::delete();
    }

    public function getURL()
    {
        if ($this->url)
            return $this->url;
        else
            return route('content_comments', $this->id);
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