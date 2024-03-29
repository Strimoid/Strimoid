<?php

namespace Strimoid\Models\Folders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\FakeFolder;

class Banned extends FakeFolder
{
    public bool $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        if (Auth::guest()) {
            redirect()->guest('login');
        }

        $builder = (new $model())->newQuery();

        $bannedGroups = Auth::user()->bannedGroups()->pluck('id');
        $builder->whereIn('group_id', $bannedGroups);

        return $builder;
    }
}
