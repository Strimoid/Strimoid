<?php

namespace Strimoid\Providers;

use Dingo\Api\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Strimoid\Api\Controllers';

    /**
     * Bootstrap any application services.
     *
     */
    public function boot(Router $api): void
    {
        $path = app_path('Api/routes.php');

        $api->version('v1', ['namespace' => $this->namespace, 'middleware' => 'bindings'], function ($api) use ($path): void {
            require $path;
        });
    }

    /**
     * Register the service provider.
     *
     */
    public function register(): void
    {
        //
    }
}
