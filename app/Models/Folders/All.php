<?php

namespace Strimoid\Models\Folders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\Content;
use Strimoid\Models\FakeFolder;

class All extends FakeFolder
{
    protected function getBuilder(string $model): Builder
    {
        $builder = (new $model())->newQuery();

        if (Auth::check()) {
            $blockedGroups = Auth::user()->blockedGroups()->pluck('id');
            $builder->whereNotIn('group_id', $blockedGroups);

            $blockedUsers = Auth::user()->blockedUsers()->pluck('id');
            $builder->whereNotIn('user_id', $blockedUsers);
        }

        return $builder;
    }

    public function contents(string $tab = null, string $sortBy = null): Builder
    {
        $builder = $this->getBuilder(Content::class);

        if (Auth::check()) {
            $blockedDomains = Auth::user()->blockedDomains();
            $builder->whereNotIn('domain', $blockedDomains);
        }

        if ($tab === 'new') {
            $builder->frontpage(false);
        } elseif ($tab === 'popular') {
            $builder->frontpage(true);
            $sortBy = $sortBy ?: 'frontpage_at';
        }

        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }
}
