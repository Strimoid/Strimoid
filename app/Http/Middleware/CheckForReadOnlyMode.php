<?php namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForReadOnlyMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
        if ($request->getMethod() != 'GET' && $this->isReadOnlyModeEnabled()) {
            throw new HttpException(503);
        }

        return $next($request);
    }

    public function isReadOnlyModeEnabled()
    {
        return file_exists($this->app->storagePath().'/framework/readonly');
    }
}
