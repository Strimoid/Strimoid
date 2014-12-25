<?php namespace Strimoid\Models;

use Str;

abstract class FakeFolder
{

    abstract protected function getBuilder($model);

    public function __construct()
    {
        $urlname = get_class($this);
        $urlname = class_basename($urlname);

        $this->urlname = Str::lower($urlname);
        $this->name = trans('groups.'. $this->urlname);
    }

    public function comments()
    {
        $builder = static::getBuilder('Comment');

        return $builder;
    }

    public function contents()
    {
        $builder = static::getBuilder('Content');

        return $builder;
    }

    public function entries()
    {
        $builder = static::getBuilder('Entry');

        return $builder;
    }

}
