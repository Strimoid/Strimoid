<?php

namespace Groups;

use Auth;
use FakeGroup;

class Downvoted extends FakeGroup {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        if (Auth::check())
        {
            $builder->where('votes.user_id', Auth::user()->_id)->where('votes.up', '!=', true);
        }

        return $builder;
    }

}