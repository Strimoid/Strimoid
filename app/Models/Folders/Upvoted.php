<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Upvoted extends FakeFolder
{
    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        if (Auth::check()) {
            $builder->where('votes.user_id', Auth::user()->_id)
                ->where('votes.up', true);
        }

        return $builder;
    }
}
