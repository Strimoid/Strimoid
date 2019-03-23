<?php

namespace Strimoid\Models\Folders;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Subscribed extends FakeFolder
{
    public $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        $subscribedGroups = Auth::user()->subscribedGroups()->pluck('id');
        $builder->whereIn('group_id', $subscribedGroups);

        $blockedUsers = Auth::user()->blockedUsers()->pluck('id');
        $builder->whereNotIn('user_id', $blockedUsers);

        return $builder;
    }
}
