<?php namespace Strimoid\Repositories; 

use Paginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Repository {

    protected function paginate(Builder $builder, $perPage)
    {
        $page = Paginator::resolveCurrentPage();
        $total = $builder->count();
        $query = $builder->forPage($page, $perPage);
        $results = $query->get();

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

}
