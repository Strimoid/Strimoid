<?php

namespace Strimoid\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class FakeFolder
{
    public string $urlname;

    public bool $isPrivate = false;

    abstract protected function getBuilder(string $model): Builder;

    public function __construct()
    {
        $urlname = static::class;
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

    public function contents(string $tab = null, string $sortBy = null): Builder
    {
        $builder = static::getBuilder(Content::class);

        if ($tab === 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    public function entries(): Builder
    {
        return static::getBuilder(Entry::class);
    }
}
