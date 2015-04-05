<?php namespace Strimoid\Models;

use Auth;
use Str;
use Strimoid\Helpers\MarkdownParser;
use Strimoid\Models\Traits\HasGroupRelationship;
use Strimoid\Models\Traits\HasUserRelationship;

/**
 * Comment model.
 *
 * @property string $_id
 * @property Content $content
 * @property string $text
 * @property string $text_source
 * @property User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|CommentReply[] $replies 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @property-read Group $group 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class Comment extends BaseModel
{
    use HasGroupRelationship, HasUserRelationship;

    protected static $rules = [
        'text' => 'required|min:1|max:5000',
    ];

    protected $appends = ['vote_state'];
    protected $table = 'comments';
    protected $fillable = ['text'];
    protected $hidden = ['_replies', 'content_id', 'text_source', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            //$comment->group_id = $comment->content->group_id;
        });

        static::created(function ($comment) {
            //$comment->content->increment('comments_count');
        });
    }

    public function content()
    {
        return $this->belongsTo(Content::class)->withTrashed();
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'parent_id')->with('user');
    }

    public function delete()
    {
        foreach ($this->replies as $reply) {
            $reply->delete();
        }

        Content::where('id', $this->content_id)->decrement('comments_count');

        return parent::delete();
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text(parse_usernames($text));
        $this->attributes['text_source'] = $text;
    }

    public function isHidden()
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->isBlockingUser($this->user);
    }

    public function getURL()
    {
        return route('content_comments', $this->content).'#'.$this->hashId();
    }

    public function canEdit()
    {
        return Auth::id() === $this->user_id && $this->replies()->count() == 0;
    }

    public function canRemove()
    {
        return Auth::id() === $this->user_id || Auth::user()->isModerator($this->group_id);
    }
}
