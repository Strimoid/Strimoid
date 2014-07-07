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

} 