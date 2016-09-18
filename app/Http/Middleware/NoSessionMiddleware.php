<?php

namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoSessionMiddleware
{
    protected $urls = [
        'i/*'
    ];

    public function handle(Request $request, Closure $next)
    {
        foreach ($this->urls as $except) {
            if ($request->is($except)) {
                config()->set('session.driver', 'array');
            }
        }

        return $next($request);
    }
}
