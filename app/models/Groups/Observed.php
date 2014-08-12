<?php

namespace Groups;

use Auth;
use FakeGroup;

class Observed extends FakeGroup {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        $observedUsers = (array) Auth::user()->_observed_users;
        $builder->whereIn('user_id', $observedUsers);

        $blockedGroups = Auth::user()->blockedGroups();
        $builder->whereNotIn('group_id', (array) $blockedGroups);

        return $builder;
    }

} 