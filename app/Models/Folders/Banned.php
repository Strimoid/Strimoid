<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Banned extends FakeFolder
{
    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        if (Auth::check()) {
            $bannedGroups = Auth::user()->bannedGroups()->lists('id');
            $builder->whereIn('group_id', $bannedGroups);
        }

        return $builder;
    }
}
