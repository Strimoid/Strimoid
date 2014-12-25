<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Moderated extends FakeFolder {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        $moderatedGroups = Auth::user()->moderatedGroups();
        $builder->whereIn('group_id', $moderatedGroups);

        return $builder;
    }

}