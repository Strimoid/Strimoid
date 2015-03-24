<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class Blocked extends FakeFolder
{
    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        if (Auth::check()) {
            $blockedGroups = Auth::user()->blockedGroups()->lists('id');
            $builder->whereIn('group_id', $blockedGroups);

            $blockedUsers = Auth::user()->blockedUsers()->lists('id');
            $builder->orWhereIn('user_id', $blockedUsers);
        }

        return $builder;
    }

    public function contents($tab, $sortBy)
    {
        $builder = static::getBuilder('Strimoid\Models\Content');

        $blockedDomains = Auth::user()->blockedDomains();
        $builder->orWhereIn('domain', $blockedDomains);

        if ($tab == 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }
}
