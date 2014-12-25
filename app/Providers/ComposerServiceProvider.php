<?php namespace Strimoid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\Factory as ViewFactory;

class ComposerServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot(ViewFactory $view)
    {
        $view->composer('global.master', 'Strimoid\Http\ViewComposers\MasterComposer');
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
