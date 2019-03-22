<?php

namespace Strimoid\Models;

use Str;

abstract class FakeFolder
{
    /**
     * @var string
     */
    public $urlname;

    /**
     * @var bool
     */
    public $isPrivate = false;

    /**
     * @param  $model  Class name of requested model
     *
     */
    abstract protected function getBuilder($model): \Illuminate\Database\Eloquent\Builder;

    public function __construct()
    {
        $urlname = get_class($this);
        $urlname = class_basename($urlname);

        $this->urlname = Str::lower($urlname);
        $this->name = trans('groups.' . $this->urlname);
    }

    public function comments(string $sortBy = null): \Illuminate\Database\Eloquent\Builder
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
    public function contents($tab = null, $sortBy = null): \Illuminate\Database\Eloquent\Builder
    {
        $builder = static::getBuilder(Content::class);

        if ($tab == 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    public function entries(): \Illuminate\Database\Eloquent\Builder
    {
        $builder = static::getBuilder(Entry::class);

        return $builder;
    }
}
