<?php namespace Strimoid\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Paginator;

abstract class Repository
{
    protected function paginate(Builder $builder, $perPage)
    {
        $page = Paginator::resolveCurrentPage();
        $total = $builder->count();
        $query = $builder->forPage($page, $perPage);
        $results = $query->get();

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }
}
