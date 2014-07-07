<?php

namespace Groups;

use Auth;
use FakeGroup;

class Banned extends FakeGroup {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        if (Auth::check())
        {
            $bannedGroups = Auth::user()->bannedGroups();
            $builder->whereIn('group_id', (array) $bannedGroups);
        }

        return $builder;
    }

}
