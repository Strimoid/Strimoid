<?php

namespace Strimoid\Handlers\Events;

use Illuminate\Events\Dispatcher;
use Strimoid\Entry\Events\EntryCreated;
use Strimoid\Entry\Events\EntryReplyCreated;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;

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
    }
}
