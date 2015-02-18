<?php namespace Strimoid\Providers; 

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider {

    /**
     * List of repositories to bind.
     *
     * @var array
     */
    protected $repositories = [
        'content', 'folder', 'group', 'user'
    ];

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $repository)
        {
            $studly = studly_case($repository);
            $contract = 'Strimoid\\Contracts\\Repositories\\'. $studly .'Repository';
            $repo = 'Strimoid\\Repositories\\'. $studly .'Repository';

            $this->app->bind($contract, $repo);
        }
    }

}
