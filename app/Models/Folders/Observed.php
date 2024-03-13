<?php

namespace Strimoid\Models\Folders;

use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Observed extends FakeFolder
{
    public bool $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = (new $model())->newQuery();

        $observedUsers = user()->followedUsers()->pluck('id');
        $builder->whereIn('user_id', $observedUsers);

        $blockedGroups = user()->blockedGroups()->pluck('id');
        $builder->whereNotIn('group_id', $blockedGroups);

        return $builder;
    }
}
