<?php

namespace Strimoid\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class Locale
{
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->check() && setting('language') !== 'auto') {
            $locale = setting('language');
        } else {
            $locale = $this->detectLocale();
        }

        \App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }

    private function detectLocale()
    {
        $userLocales = \Agent::languages();

        foreach (['pl', 'en'] as $locale) {
            if (in_array($locale, $userLocales)) {
                return $locale;
            }
        }

        return config('app.locale');
    }
}
