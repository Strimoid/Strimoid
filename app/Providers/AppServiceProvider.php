<?php namespace Strimoid\Providers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Pdp\Parser;
use Pdp\PublicSuffixListManager;
use Strimoid\Helpers\OEmbed;
use Strimoid\Services\Settings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('local')) {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }

        $rollbarToken = config('services.rollbar.access_token');

        if (! empty($rollbarToken)) {
            $this->app->register('Jenssegers\Rollbar\RollbarServiceProvider');
        }

        Carbon::setLocale('pl');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
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

        $this->app->bind('settings', function () {
           return new Settings();
        });
    }
}
