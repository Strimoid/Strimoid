<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\Builder;
use Str;

abstract class FakeFolder
{
    public string $urlname;

    public bool $isPrivate = false;

    abstract protected function getBuilder(string $model): Builder;

    public function __construct()
    {
        $urlname = get_class($this);
        $urlname = class_basename($urlname);

        $this->urlname = Str::lower($urlname);
        $this->name = trans('groups.' . $this->urlname);
    }

    public function comments(string $sortBy = null): Builder
    {
        $builder = static::getBuilder(Comment::class);
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    /**
     * @param null $tab
     * @param null $sortBy
     *
     */
    public function contents($tab = null, $sortBy = null): Builder
    {
        $builder = static::getBuilder(Content::class);

        if ($tab == 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    public function entries(): Builder
    {
        $builder = static::getBuilder(Entry::class);

        return $builder;
    }
}
