<?php

namespace Strimoid\Entry\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Strimoid\Models\Entry;

class EntryCreated implements ShouldBroadcast
{
    public Entry $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function broadcastAs(): string
    {
        return 'entry.created';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('entries');
    }
}
