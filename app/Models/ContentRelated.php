<?php namespace Strimoid\Models;

use Str;
use Strimoid\Models\Traits\HasThumbnail;

/**
 * Strimoid\Models\ContentRelated
 *
 * @property-read \Strimoid\Models\Content $content 
 * @property-read \Strimoid\Models\User $user 
 * @property-write mixed $nsfw 
 * @property-write mixed $eng 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class ContentRelated extends BaseModel
{
    use HasThumbnail;

    protected static $rules = [
        'title' => 'required|min:1|max:128',
        'url'   => 'required|url_custom',
    ];

    protected $table = 'content_related';
    protected $hidden = ['content_id', 'user_id', 'updated_at'];
    protected $fillable = ['title', 'nsfw', 'eng', 'url'];

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
        Content::where('id', $this->content_id)->decrement('related_count');

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
        return $this->url ?: route('content_comments', $this->getKey());
    }
}
