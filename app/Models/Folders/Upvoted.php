<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Upvoted extends FakeFolder
{
    protected function getBuilder($model)
    {
        if (Auth::guest()) {
            return redirect()->guest('login');
        }

        $builder = with(new $model())->newQuery();

        $builder->where('votes.user_id', Auth::id())
            ->where('votes.up', true);

        return $builder;
    }
}
