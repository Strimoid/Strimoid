<?php namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as IlluminateCsrf;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends IlluminateCsrf
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Illuminate\Session\TokenMismatchException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->canSkipVerification($request)) {
            return $next($request);
        }

        if ($this->isReading($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException();
    }

    /**
     * Check if verification should be omitted.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function canSkipVerification($request)
    {
        return starts_with($request->getPathInfo(), [
            '/oauth2/', '/api/', '/pusher/',
        ]);
    }
}
