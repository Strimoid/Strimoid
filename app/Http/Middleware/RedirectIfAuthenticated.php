<?php

namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    protected Guard $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->check()) {
            return new RedirectResponse(url('/'));
        }

        return $next($request);
    }
}
