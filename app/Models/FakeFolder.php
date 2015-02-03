<?php namespace Strimoid\Models;

use Str;

abstract class FakeFolder
{

    /**
     * @var string
     */
    public $urlname;

    /**
     * @param  $model  Class name of requested model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract protected function getBuilder($model);

    public function __construct()
    {
        $urlname = get_class($this);
        $urlname = class_basename($urlname);

        $this->urlname = Str::lower($urlname);
        $this->name = trans('groups.'. $this->urlname);
    }

    /**
     * @param  string  $sortBy
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function comments($sortBy = null)
    {
        $builder = static::getBuilder('Strimoid\Models\Comment');
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    /**
     * @param null $tab
     * @param null $sortBy
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function contents($tab = null, $sortBy = null)
    {
        $builder = static::getBuilder('Strimoid\Models\Content');

        if ($tab == 'popular') $builder->popular();
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function entries()
    {
        $builder = static::getBuilder('Strimoid\Models\Entry');

        return $builder;
    }

}
