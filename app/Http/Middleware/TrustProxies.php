<?php

namespace Strimoid\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    protected $proxies = ['10.0.0.0/8'];
}
