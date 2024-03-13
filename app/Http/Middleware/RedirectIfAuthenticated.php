<?php

namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function __construct(protected Guard $auth, private readonly \Illuminate\Contracts\Routing\UrlGenerator $urlGenerator)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->check()) {
            return new RedirectResponse($this->urlGenerator->to('/'));
        }

        return $next($request);
    }
}
