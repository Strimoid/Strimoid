<?php namespace Strimoid\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware',
        'Strimoid\Http\Middleware\CheckForReadOnlyMode',
        'Strimoid\Http\Middleware\VerifyCsrfToken',
        'Strimoid\Http\Middleware\RateLimit',
        'Strimoid\Http\Middleware\NotificationMarkRead',
        'Strimoid\Http\Middleware\Pjax',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => 'Strimoid\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest'      => 'Strimoid\Http\Middleware\RedirectIfAuthenticated',
        'oauth'      => 'Strimoid\Http\Middleware\OAuth',
    ];
}
