<?php

namespace Strimoid\Models\Folders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\FakeFolder;

class Saved extends FakeFolder
{
    public bool $isPrivate = true;

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();
        $builder->whereHas('saves', function ($q): void {
            $q->where('user_id', Auth::id());
        });

        return $builder;
    }
}
