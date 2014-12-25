<?php namespace Strimoid\Providers;

use UserSettings;
use Illuminate\Support\ServiceProvider;

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
