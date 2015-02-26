<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Subscribed extends FakeFolder
{
    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        $subscribedGroups = Auth::user()->subscribedGroups();
        $builder->whereIn('group_id', $subscribedGroups);

        $blockedUsers = Auth::user()->blockedUsers();
        $builder->whereNotIn('user_id', (array) $blockedUsers);

        return $builder;
    }
}
