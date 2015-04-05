<?php namespace Strimoid\Models;

use Strimoid\Helpers\MarkdownParser;

/**
 * Strimoid\Models\ConversationMessage
 *
 * @property-read Conversation $conversation 
 * @property-read User $user 
 * @property-write mixed $text 
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class ConversationMessage extends BaseModel
{
    protected $table = 'conversation_messages';
    protected $visible = [
        'id', 'conversation', 'created_at', 'user', 'text',
    ];

    protected static $unguarded = true;

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setTextAttribute($text)
    {
        $this->attributes['text'] = MarkdownParser::instance()->text($text);
        $this->attributes['text_source'] = $text;
    }
}
