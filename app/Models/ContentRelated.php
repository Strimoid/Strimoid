<?php

namespace Strimoid\Models;

use Strimoid\Models\Traits\HasThumbnail;
use Strimoid\Models\Traits\HasVotes;

class ContentRelated extends BaseModel
{
    use HasThumbnail, HasVotes;

    protected static $rules = [
        'title' => 'required|min:1|max:128',
        'url' => 'required|url_custom',
    ];

    protected $table = 'content_related';
    protected $hidden = ['content_id', 'user_id', 'updated_at'];
    protected $fillable = ['title', 'nsfw', 'eng', 'url'];

    public static function boot(): void
    {
        static::creating(function ($related): void {
            $related->group_id = $related->content->group_id;
        });

        static::created(function ($related): void {
            $related->content->increment('related_count');
        });

        static::bootTraits();
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function delete()
    {
        Content::where('id', $this->content_id)->decrement('related_count');

        return parent::delete();
    }

    public function getURL()
    {
        return $this->url ?: route('content_comments', $this->getKey());
    }

    public function setNsfwAttribute($value): void
    {
        $this->attributes['nsfw'] = toBool($value);
    }

    public function setEngAttribute($value): void
    {
        $this->attributes['eng'] = toBool($value);
    }
}
