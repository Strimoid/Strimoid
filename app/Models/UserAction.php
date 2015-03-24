<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasUserRelationship;
use Strimoid\Models\Traits\NoUpdatedAt;

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
