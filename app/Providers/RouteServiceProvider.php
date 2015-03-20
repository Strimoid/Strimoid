<?php namespace Strimoid\Providers;

use Hashids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
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
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->bindModel($router, 'content', 'Content');
        $this->bindModel($router, 'related', 'ContentRelated');
        $this->bindModel($router, 'notification', 'Notification');
        $this->bindModel($router, 'comment', 'Comment');
        $this->bindModel($router, 'comment_reply', 'CommentReply');
        $this->bindModel($router, 'entry', 'Entry');
        $this->bindModel($router, 'entry_reply', 'EntryReply');
        $this->bindModel($router, 'user', 'User');

        parent::boot($router);
    }

    /**
     * Bind object resolve function for given model class.
     *
     * @param Router $router
     * @param $key
     * @param $className
     */
    public function bindModel(Router $router, $key, $className)
    {
        $router->bind($key, function($value, $route) use($className) {
            $class = 'Strimoid\\Models\\'. $className;

            if (in_array($className, ['Group', 'User'])) {
                return $class::name($value)->firstOrFail();
            }

            $ids = Hashids::decode($value);

            if (!count($ids)) throw new ModelNotFoundException;

            return $class::findOrFail($ids[0]);
        });
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
