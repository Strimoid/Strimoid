<?php namespace Strimoid\Models;

class GroupSubscriber extends BaseModel
{
    protected $table = 'group_subscribers';

    public static function boot()
    {
        parent::boot();

        self::created(function (GroupSubscriber $sub) {
            $sub->group()->increment('subscribers');
        });

        self::deleted(function (GroupSubscriber $sub) {
            $sub->group()->decrement('subscribers');
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
