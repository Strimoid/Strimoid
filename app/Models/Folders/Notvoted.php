<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Notvoted extends FakeFolder
{
    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        if (Auth::check()) {
            $builder->where('votes.user_id', '!=', Auth::id());
        }

        return $builder;
    }
}
