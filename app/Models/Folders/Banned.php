<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Banned extends FakeFolder
{
    protected function getBuilder($model)
    {
        if (Auth::guest()) {
            redirect()->guest('login');
        }

        $builder = with(new $model())->newQuery();

        $bannedGroups = Auth::user()->bannedGroups()->lists('id');
        $builder->whereIn('group_id', $bannedGroups);

        return $builder;
    }
}
