<?php namespace Strimoid\Handlers\Events;

use Strimoid\Models\Entry;
use Strimoid\Models\Notification;
use Vinkla\Pusher\Facades\Pusher;

class PubSubHandler
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: '.Entry::class,
            self::class.'@onNewEntry');
        $events->listen('eloquent.created: '.Notification::class,
            self::class.'@onNewNotification');
    }

    public function onNewEntry(Entry $entry)
    {
        Pusher::trigger('entries', 'new-entry', $entry);
    }

    public function onNewNotification(Notification $notification)
    {
        foreach ($notification->targets as $target) {
            $channelName = 'private-u-'.$target->user_id;

            $notification = [
                'tag'   => mid_to_b58($notification->getKey()),
                'type'  => $notification->getTypeDescription(),
                'title' => $notification->title,
                'img'   => $notification->getThumbnailPath(),
                'url'   => $notification->getURL(true),
            ];

            Pusher::trigger($channelName, 'new-notification', $notification);
        }
    }
}
