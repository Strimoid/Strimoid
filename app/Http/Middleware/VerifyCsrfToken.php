<?php namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as IlluminateCsrf;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends IlluminateCsrf {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ($this->isOAuth($request))
        {
            return $next($request);
        }

        if ($this->isReading($request) || $this->tokensMatch($request))
        {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException;
    }

    /**
     * Check if request is made to OAuth Authorization Server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isOAuth($request)
    {
        return starts_with($request->getPathInfo() , '/oauth2/');
    }

}
