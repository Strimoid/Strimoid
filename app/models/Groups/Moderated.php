<?php

namespace Groups;

use Auth;
use FakeGroup;

class Moderated extends FakeGroup {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        $moderatedGroups = Auth::user()->moderatedGroups();
        $builder->whereIn('group_id', $moderatedGroups);

        return $builder;
    }

}