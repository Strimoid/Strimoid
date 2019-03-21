<?php

namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Notvoted extends FakeFolder
{
    public $isPrivate = true;

    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();
        $builder->where('votes.user_id', '!=', Auth::id());

        return $builder;
    }
}
