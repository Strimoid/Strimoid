<?php

namespace Strimoid\Models\Folders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\FakeFolder;

class Subscribed extends FakeFolder
{
    public bool $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = (new $model())->newQuery();

        $subscribedGroups = Auth::user()->subscribedGroups()->pluck('id');
        $builder->whereIn('group_id', $subscribedGroups);

        $blockedUsers = Auth::user()->blockedUsers()->pluck('id');
        $builder->whereNotIn('user_id', $blockedUsers);

        return $builder;
    }
}
