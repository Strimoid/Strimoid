<?php

namespace Strimoid\Models\Folders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Moderated extends FakeFolder
{
    public bool $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        $moderatedGroups = Auth::user()->moderatedGroups()->select('groups.id')->pluck('id');
        $builder->whereIn('group_id', $moderatedGroups);

        return $builder;
    }
}
