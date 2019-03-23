<?php

namespace Strimoid\Models\Folders;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Upvoted extends FakeFolder
{
    public $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        $builder->where('votes.user_id', Auth::id())
            ->where('votes.up', true);

        return $builder;
    }
}
