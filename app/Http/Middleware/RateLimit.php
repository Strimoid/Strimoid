<?php namespace Strimoid\Http\Middleware;

use Closure;
use GrahamCampbell\Throttle\Throttle;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class RateLimit
{

    /**
     * The throttle instance.
     *
     * @var \GrahamCampbell\Throttle\Throttle
     */
    protected $throttle;

    /**
     * Create a new instance.
     *
     * @param \GrahamCampbell\Throttle\Throttle $throttle
     */
    public function __construct(Throttle $throttle)
    {
        $this->throttle = $throttle;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isEnabledFor($request))
        {
            $limit = 25; // request limit
            $time = 10; // ban time

            if (false === $this->throttle->attempt($request, $limit, $time))
            {
                throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceed.');
            }
        }

        return $next($request);
    }

    /**
     * Shall be throttle limit enabled for given request?
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function isEnabledFor($request)
    {
        // Limit only POST requests
        if ($request->getMethod() != 'POST')
        {
            return false;
        }

        // Disable throttle limit for voting
        if (starts_with($request->getPathInfo(), '/ajax/vote/'))
        {
            return false;
        }

        return true;
    }
}
