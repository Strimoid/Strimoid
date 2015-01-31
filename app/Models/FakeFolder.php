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
        $builder = static::getBuilder('Strimoid\Models\Comment');

        return $builder;
    }

    public function contents()
    {
        $builder = static::getBuilder('Strimoid\Models\Content');

        return $builder;
    }

    public function entries()
    {
        $builder = static::getBuilder('Strimoid\Models\Entry');

        return $builder;
    }

    public function __get($name)
    {
        if ($name == 'urlname')
        {
            $className = get_class($this);
            return strtolower($className);
        }
    }

}
