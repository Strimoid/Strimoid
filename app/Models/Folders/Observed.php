<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Observed extends FakeFolder
{
    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        $observedUsers = (array) Auth::user()->_observed_users;
        $builder->whereIn('user_id', $observedUsers);

        $blockedGroups = Auth::user()->blockedGroups();
        $builder->whereNotIn('group_id', (array) $blockedGroups);

        return $builder;
    }
}
