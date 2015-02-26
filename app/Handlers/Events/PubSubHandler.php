<?php namespace Strimoid\Handlers\Events;

use Realtime;
use Strimoid\Models\Notification;

class PubSubHandler
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: '. Notification::class,
            self::class .'@onNewNotification');
    }

    public function onNewNotification(Notification $notification)
    {
        foreach ($notification->targets as $target) {
            $channelName = 'u.'.$target->user_id;
            Realtime::publish($channelName, [
                'tag'   => mid_to_b58($notification->getKey()),
                'type'  => $notification->getTypeDescription(),
                'title' => $notification->title,
                'img'   => $notification->getThumbnailPath(),
                'url'   => $notification->getURL(true),
            ]);
        }
    }
}
