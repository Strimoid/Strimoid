<?php

namespace Strimoid\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function __construct(private \Illuminate\Contracts\Broadcasting\Factory $broadcastingFactory)
    {
        parent::__construct();
    }
    public function boot(): void
    {
        $this->broadcastingFactory->routes();

        /*
         * Authenticate the user's personal channel...
         */
        $this->broadcastingFactory->channel('App.User.*', fn ($user, $userId) => (int) $user->id === (int) $userId);
    }
}
