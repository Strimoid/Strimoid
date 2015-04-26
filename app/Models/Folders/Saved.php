<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Saved extends FakeFolder
{
    protected function getBuilder($model)
    {
        if (Auth::guest()) {
            redirect()->guest('login');
        }

        $builder = with(new $model())->newQuery();
        $builder->whereHas('saves', function ($q) {
            $q->where('user_id', Auth::id());
        });

        return $builder;
    }
}
