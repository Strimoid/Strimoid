<?php namespace Strimoid\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as IlluminateVerifyCsrfToken;

class VerifyCsrfToken extends IlluminateVerifyCsrfToken
{
    /* @var array */
    protected $except = ['oauth2/', 'api/', 'pusher/'];
}
