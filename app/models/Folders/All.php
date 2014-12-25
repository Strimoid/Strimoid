<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class All extends FakeFolder {

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

        if (Auth::check()) {
            $blockedDomains = Auth::user()->blocked_domains;
            $builder->whereNotIn('domain', $blockedDomains);
        }

        return $builder;
    }

} 