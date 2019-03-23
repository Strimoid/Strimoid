<?php

namespace Strimoid\Models\Folders;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Notvoted extends FakeFolder
{
    public $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();
        $builder->where('votes.user_id', '!=', Auth::id());

        return $builder;
    }
}
