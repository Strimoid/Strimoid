<?php

namespace Strimoid\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    protected array $repositories = [
        'content', 'folder', 'group', 'user',
    ];

    public function register(): void
    {
        foreach ($this->repositories as $repository) {
            $studly = studly_case($repository);
            $contract = 'Strimoid\\Contracts\\Repositories\\' . $studly . 'Repository';
            $repo = 'Strimoid\\Repositories\\' . $studly . 'Repository';

            $this->app->bind($contract, $repo);
        }
    }
}
