<?php namespace Strimoid\Models\Folders;

use Auth;
use Strimoid\Models\Content;
use Strimoid\Models\FakeFolder;

class Blocked extends FakeFolder
{
    protected function getBuilder($model)
    {
        if (Auth::guest()) {
            redirect()->guest('login');
        }

        $builder = with(new $model())->newQuery();

        $blockedGroups = Auth::user()->blockedGroups()->lists('id');
        $builder->whereIn('group_id', $blockedGroups);

        $blockedUsers = Auth::user()->blockedUsers()->lists('id');
        $builder->orWhereIn('user_id', $blockedUsers);

        return $builder;
    }

    public function contents($tab = null, $sortBy = null)
    {
        if (Auth::guest()) {
            return redirect()->guest('login');
        }

        $builder = static::getBuilder(Content::class);

        $blockedDomains = Auth::user()->blockedDomains();
        $builder->orWhereIn('domain', $blockedDomains);

        if ($tab == 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }
}
