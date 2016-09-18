<?php namespace Strimoid\Providers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Pdp\Parser;
use Pdp\PublicSuffixListManager;
use Strimoid\Helpers\OEmbed;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->environment('local')) {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }

        $dsn = config('services.raven.dsn');

        if (!empty($dsn)) {
            $this->app->register(\Jenssegers\Raven\RavenServiceProvider::class);
        }

        $locale = config('app.locale');
        Carbon::setLocale($locale);

        Paginator::$defaultView = 'pagination::bootstrap-4';
        Paginator::$defaultSimpleView = 'pagination::simple-bootstrap-4';

        \Request::setTrustedProxies(['172.16.0.0/12', 'fd00::/8']);
    }

    public function register()
    {
        $this->app->bind('guzzle', function () {
            return new Client([
                'connect_timeout' => 3,
                'timeout'         => 10,
            ]);
        });

        $this->app->bind('pdp', function () {
            $pslManager = new PublicSuffixListManager();
            $parser = new Parser($pslManager->getList());

            return $parser;
        });

        $this->app->bind('oembed', function () {
            return new OEmbed();
        });
    }
}
