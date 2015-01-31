<?php namespace Strimoid\Providers;

use GuzzleHttp\Client;
use Strimoid\Helpers\OEmbed;
use Strimoid\Models\UserSettings;
use Illuminate\Support\ServiceProvider;
use Pdp\PublicSuffixListManager;
use Pdp\Parser;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('guzzle', function()
        {
            return new Client([
                'connect_timeout' => 3,
                'timeout' => 10,
            ]);
        });

        $this->app->bind('settings', function()
        {
            return new UserSettings();
        });

        $this->app->bind('pdp', function()
        {
            $pslManager = new PublicSuffixListManager();
            $parser = new Parser($pslManager->getList());

            return $parser;
        });

        $this->app->bind('oembed', function()
        {
            return new OEmbed();
        });
    }

}
