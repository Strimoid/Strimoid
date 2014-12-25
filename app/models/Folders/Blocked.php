<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Blocked extends FakeFolder {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        if (Auth::check())
        {
            $blockedGroups = Auth::user()->blockedGroups();
            $builder->whereIn('group_id', (array) $blockedGroups);

            $blockedUsers = Auth::user()->blockedUsers();
            $builder->orWhereIn('user_id', (array) $blockedUsers);
        }

        return $builder;
    }

    public function contents()
    {
        $builder = static::getBuilder('Content');

        $blockedDomains = Auth::user()->blockedDomains();
        $builder->orWhereIn('domain', $blockedDomains);

        return $builder;
    }

}
