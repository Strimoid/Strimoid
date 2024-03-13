<?php

namespace Strimoid\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes();

        Broadcast::channel(
            'notifications.{userId}',
            fn ($user, $userId) =>
            $user->hashId() === $userId
        );
    }
}
