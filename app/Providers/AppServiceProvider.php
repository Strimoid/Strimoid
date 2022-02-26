<?php

namespace Strimoid\Providers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Pdp\Rules;
use Strimoid\Helpers\OEmbed;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }

        $dsn = config('services.raven.dsn');

        if (!empty($dsn)) {
            $this->app->register('Jenssegers\Raven\RavenServiceProvider');
        }

        Paginator::useBootstrap();
    }

    public function register(): void
    {
        $this->app->bind('guzzle', fn () => new Client([
            'connect_timeout' => 3,
            'timeout' => 10,
        ]));

        $this->app->bind('pdp', function () {
            $path = base_path('vendor/jeremykendall/php-domain-parser/data/pdp-PSL_FULL_5a3cc7f81795bb2e48e848af42d287b4.cache');
            $serialized = file_get_contents($path);
            $unserialized = unserialize($serialized);
            $data = json_decode($unserialized, true);

            return new Rules($data, IDNA_DEFAULT, IDNA_DEFAULT);
        });

        $this->app->bind('oembed', fn () => new OEmbed());
    }
}
