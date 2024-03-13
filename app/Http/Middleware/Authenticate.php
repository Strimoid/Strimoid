<?php

namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class Authenticate
{
    public function __construct(protected Guard $auth, private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private readonly \Illuminate\Routing\Redirector $redirector)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return $this->responseFactory->make('Unauthorized.', 401);
            }

            return $this->redirector->guest('login');
        }

        return $next($request);
    }
}
