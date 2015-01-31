<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Saved extends FakeFolder {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();
        $builder->where('saves.user_id', Auth::id());

        return $builder;
    }

}
