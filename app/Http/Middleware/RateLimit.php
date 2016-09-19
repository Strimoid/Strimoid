<?php

namespace Strimoid\Http\Middleware;

use Closure;
use GrahamCampbell\Throttle\Throttle;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class RateLimit
{
    /** @var \GrahamCampbell\Throttle\Throttle */
    protected $throttle;

    public function __construct(Throttle $throttle)
    {
        $this->throttle = $throttle;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->isEnabledFor($request)) {
            $limit = 25; // request limit
            $time = 10; // ban time

            if (false === $this->throttle->attempt($request, $limit, $time)) {
                throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceed.');
            }
        }

        return $next($request);
    }

    protected function isEnabledFor(Request $request) : bool
    {
        // Limit only POST requests
        if ($request->getMethod() != 'POST') {
            return false;
        }

        // Disable throttle limit for voting
        if ($request->is('ajax/vote/')) {
            return false;
        }

        return true;
    }
}
