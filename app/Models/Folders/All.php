<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\FakeFolder;

class All extends FakeFolder
{
    protected function getBuilder($model)
    {
        $builder = with(new $model())->newQuery();

        if (Auth::check()) {
            $blockedGroups = Auth::user()->blockedGroups()->lists('id');
            $builder->whereNotIn('group_id', $blockedGroups);

            $blockedUsers = Auth::user()->blockedUsers()->lists('id');
            $builder->whereNotIn('user_id', $blockedUsers);
        }

        return $builder;
    }

    public function contents($tab = null, $sortBy = null)
    {
        $builder = static::getBuilder('Strimoid\Models\Content');

        if (Auth::check()) {
            $blockedDomains = Auth::user()->blockedDomains();
            $builder->whereNotIn('domain', $blockedDomains);
        }

        if ($tab == 'new') {
            $builder->frontpage(false);
        } elseif ($tab == 'popular') {
            $builder->frontpage(true);
            $sortBy = $sortBy ?: 'frontpage_at';
        }

        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }
}
