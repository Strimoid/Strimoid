<?php

namespace Strimoid\Handlers\Events;

use Illuminate\Events\Dispatcher;
use Pusher\Laravel\Facades\Pusher;
use Strimoid\Entry\Events\EntryCreated;
use Strimoid\Entry\Events\EntryReplyCreated;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Notification;

class PubSubHandler
{
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            'eloquent.created: ' . Entry::class,
            fn (Entry $entry) => event(new EntryCreated($entry))
        );
        $events->listen(
            'eloquent.created: ' . EntryReply::class,
            fn (EntryReply $reply) => event(new EntryReplyCreated($reply))
        );
        // $events->listen('eloquent.created: ' . Notification::class, self::class . '@onNewNotification');
    }

    public function onNewNotification(Notification $notification): void
    {
        foreach ($notification->targets as $target) {
            $channelName = 'privateU' . $target->id;
            $notificationData = [
                'id' => $notification->hashId(),
                'type' => $notification->getTypeDescription(),
                'title' => $notification->title,
                'img' => $notification->getThumbnailPath(),
                'url' => $notification->getURL(),
            ];

            // Pusher::trigger($channelName, 'new-notification', $notificationData);
        }
    }
}
