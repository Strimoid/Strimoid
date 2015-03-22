<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\NoUpdatedAt;

class GroupSubscriber extends BaseModel
{
    use NoUpdatedAt;

    protected $table = 'user_subscribed_groups';

    public static function boot()
    {
        parent::boot();

        self::created(function (GroupSubscriber $sub) {
            $sub->group()->increment('subscribers_count');
        });

        self::deleted(function (GroupSubscriber $sub) {
            $sub->group()->decrement('subscribers_count');
        });
    }

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group')
            ->orderBy('name', 'asc');
    }

    public function user()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }
}
