<?php namespace Strimoid\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $repositories = [
        'content', 'folder', 'group', 'user',
    ];

    public function register()
    {
        foreach ($this->repositories as $repository) {
            $studly = studly_case($repository);
            $contract = 'Strimoid\\Contracts\\Repositories\\'.$studly.'Repository';
            $repo = 'Strimoid\\Repositories\\'.$studly.'Repository';

            $this->app->bind($contract, $repo);
        }
    }
}
