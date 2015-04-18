<?php namespace Strimoid\Models;

class UserAction extends BaseModel
{
    use HasUserRelationship, NoUpdatedAt;

    protected $table = 'user_actions';
    protected static $unguarded = true;

    public function element()
    {
        return $this->morphTo();
    }
}
