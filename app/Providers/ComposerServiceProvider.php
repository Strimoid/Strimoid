<?php namespace Strimoid\Providers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;
use Strimoid\Http\ViewComposers\JavascriptComposer;
use Strimoid\Http\ViewComposers\MasterComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @param ViewFactory $view
     */
    public function boot(ViewFactory $view)
    {
        $view->composer('global.master', MasterComposer::class);
        $view->composer('global.master', JavascriptComposer::class);
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
