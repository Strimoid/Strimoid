<?php

namespace Strimoid\Providers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;
use Strimoid\Http\ViewComposers\GroupBarComposer;
use Strimoid\Http\ViewComposers\JavascriptComposer;
use Strimoid\Http\ViewComposers\MasterComposer;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot(ViewFactory $view)
    {
        $view->composer('global.master', MasterComposer::class);
        $view->composer('global.master', JavascriptComposer::class);
        $view->composer('global.parts.groupbar', GroupBarComposer::class);
    }

    public function register()
    {
    }
}
