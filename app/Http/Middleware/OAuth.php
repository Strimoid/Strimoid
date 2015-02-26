<?php namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Auth\Guard;
use League\OAuth2\Server\ResourceServer;

class OAuth
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var ResourceServer
     */
    protected $server;

    /**
     * Create a new filter instance.
     *
     * @param Guard          $auth
     * @param ResourceServer $server
     */
    public function __construct(Guard $auth, ResourceServer $server)
    {
        $this->auth = $auth;
        $this->server = $server;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
