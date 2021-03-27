<?php

namespace Strimoid\Facades;

use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Facade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * @method static ResponseInterface get(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface head(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface put(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface post(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface patch(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface delete(string|UriInterface $uri, array $options = [])
 * @method static Promise\PromiseInterface getAsync(string|UriInterface $uri, array $options = [])
 * @method static Promise\PromiseInterface headAsync(string|UriInterface $uri, array $options = [])
 * @method static Promise\PromiseInterface putAsync(string|UriInterface $uri, array $options = [])
 * @method static Promise\PromiseInterface postAsync(string|UriInterface $uri, array $options = [])
 * @method static Promise\PromiseInterface patchAsync(string|UriInterface $uri, array $options = [])
 * @method static Promise\PromiseInterface deleteAsync(string|UriInterface $uri, array $options = [])
 */
class Guzzle extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'guzzle';
    }
}
