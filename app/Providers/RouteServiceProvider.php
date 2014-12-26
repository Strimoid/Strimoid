<?php namespace Strimoid\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Strimoid\Http\Controllers';
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->model('content', 'Strimoid\Models\Content');
        $router->model('related', 'Strimoid\Models\ContentRelated');
        $router->model('notification', 'Strimoid\Models\Notification');
        $router->model('comment', 'Strimoid\Models\Comment');
        $router->model('comment_reply', 'Strimoid\Models\CommentReply');
        $router->model('entry', 'Strimoid\Models\Entry');
        $router->model('entry_reply', 'Strimoid\Models\EntryReply');

        parent::boot($router);
    }
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->loadRoutesFrom(app_path('Http/routes.php'));
    }

}