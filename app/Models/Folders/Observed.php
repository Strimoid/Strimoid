<?php

namespace Strimoid\Models\Folders;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Observed extends FakeFolder
{
    public $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        $observedUsers = Auth::user()->followedUsers()->pluck('id');
        $builder->whereIn('user_id', $observedUsers);

        $blockedGroups = Auth::user()->blockedGroups()->pluck('id');
        $builder->whereNotIn('group_id', (array) $blockedGroups);

        return $builder;
    }
}
