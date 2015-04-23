<?php namespace Strimoid\Providers;

use Hashids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\ContentRelated;
use Strimoid\Models\Conversation;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Group;
use Strimoid\Models\Notification;
use Strimoid\Models\User;

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
        $this->bindModel($router, 'content', Content::class);
        $this->bindModel($router, 'related', ContentRelated::class);
        $this->bindModel($router, 'notification', Notification::class);
        $this->bindModel($router, 'comment', Comment::class);
        $this->bindModel($router, 'comment_reply', CommentReply::class);
        $this->bindModel($router, 'entry', Entry::class);
        $this->bindModel($router, 'entry_reply', EntryReply::class);
        $this->bindModel($router, 'group', Group::class);
        $this->bindModel($router, 'user', User::class);
        $this->bindModel($router, 'conversation', Conversation::class);

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
            if (ends_with($className, ['Group', 'User'])) {
                return $className::name($value)->firstOrFail();
            }

            $ids = Hashids::decode($value);

            if (!count($ids)) {
                throw new ModelNotFoundException;
            }

            return $className::findOrFail($ids[0]);
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
