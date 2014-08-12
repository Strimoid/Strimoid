<?php

namespace Groups;

use Auth;
use FakeGroup;

class Subscribed extends FakeGroup {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        $subscribedGroups = Auth::user()->subscribedGroups();
        $builder->whereIn('group_id', $subscribedGroups);

        $blockedUsers = Auth::user()->blockedUsers();
        $builder->whereNotIn('user_id', (array) $blockedUsers);

        return $builder;
    }

} 