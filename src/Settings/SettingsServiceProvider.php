<?php

namespace Strimoid\Settings;

use Illuminate\Support\ServiceProvider;
use Strimoid\Settings\Services\SettingsService;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $settingsPath = base_path('src/Settings/settings.php');
        $this->loadSettingsFrom($settingsPath);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton('settings', fn () => new SettingsService());
    }

    /**
     * Load settings from given path.
     */
    protected function loadSettingsFrom(string $path)
    {
        return require $path;
    }
}
