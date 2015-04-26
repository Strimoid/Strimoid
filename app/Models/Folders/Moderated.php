<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Moderated extends FakeFolder
{
    protected function getBuilder($model)
    {
        if (Auth::guest()) {
            redirect()->guest('login');
        }

        $builder = with(new $model())->newQuery();

        $moderatedGroups = Auth::user()->moderatedGroups()->select('groups.id')->lists('id');
        $builder->whereIn('group_id', $moderatedGroups);

        return $builder;
    }
}
