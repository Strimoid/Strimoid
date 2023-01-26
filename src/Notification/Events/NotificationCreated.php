<?php

namespace Strimoid\Notification\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Strimoid\Models\Notification;
use Vinkla\Hashids\Facades\Hashids;

class NotificationCreated implements ShouldBroadcast
{
    public function __construct(public Notification $notification)
    {
    }

    public function broadcastAs(): string
    {
        return 'notification.created';
    }

    public function broadcastOn(): array
    {
        return $this->notification->targets->map(
            fn($target) => new PrivateChannel('notifications.' . Hashids::encode($target->id))
        )->toArray();
    }
}
