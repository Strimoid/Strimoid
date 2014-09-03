<?php

namespace Groups;

use Auth;
use FakeGroup;

class All extends FakeGroup {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        if (Auth::check())
        {
            $blockedGroups = Auth::user()->blockedGroups();
            $builder->whereNotIn('group_id', (array) $blockedGroups);

            $blockedUsers = Auth::user()->blockedUsers();
            $builder->whereNotIn('user_id', (array) $blockedUsers);
        }

        return $builder;
    }

    public function contents()
    {
        $builder = static::getBuilder('Content');

        $blockedDomains = Auth::user()->blockedDomains();
        $builder->whereNotIn('domain', $blockedDomains);

        return $builder;
    }

} 