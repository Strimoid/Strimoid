<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Saved extends FakeFolder
{
    public $isPrivate = true;

    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();
        $builder->whereHas('saves', function ($q) {
            $q->where('user_id', Auth::id());
        });

        return $builder;
    }
}
