<?php namespace Strimoid\Providers;

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
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
    }

}
