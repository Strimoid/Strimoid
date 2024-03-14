<?php

namespace Strimoid\Providers;

use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Pdp\Rules;
use Strimoid\Helpers\OEmbed;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        $dsn = config('services.raven.dsn');

        if (!empty($dsn)) {
            $this->app->register('Jenssegers\Raven\RavenServiceProvider');
        }

        Paginator::useBootstrap();

        Blade::directive('ucFirstLang', function (string $key) {
            return "<?php echo Str::ucfirst(trans($key)) ?>";
        });
    }

    public function register(): void
    {
        $this->app->bind('guzzle', fn () => new Client([
            'connect_timeout' => 3,
            'timeout' => 10,
        ]));

        $this->app->bind('pdp', function () {
            $path = resource_path('public-suffix-list.dat');
            return Rules::fromPath($path);
        });

        $this->app->bind('oembed', fn () => new OEmbed());
    }
}
