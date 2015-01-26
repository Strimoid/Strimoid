<?php namespace Strimoid\Http\Middleware;

use Closure;
use GrahamCampbell\Throttle\Throttle;
use Illuminate\Contracts\Routing\Middleware;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class RateLimit {

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
     *
     * @return void
     */
    public function __construct(RateLimit $throttle)
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
        // Limit only POST requests
        if ($request->getMethod() == 'POST')
        {
            $limit = 25; // request limit
            $time = 10; // ban time

            if (false === $this->throttle->attempt($request, $limit, $time)) {
                throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceed.');
            }
        }

        return $next($request);
    }

}
