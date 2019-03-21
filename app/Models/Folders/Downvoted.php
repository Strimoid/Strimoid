<?php

namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Downvoted extends FakeFolder
{
    public $isPrivate = true;

    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        $builder->where('votes.user_id', Auth::id())
            ->where('votes.up', '!=', true);

        return $builder;
    }
}
