<?php

namespace Strimoid\Entry\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Strimoid\Models\EntryReply;
use Vinkla\Hashids\Facades\Hashids;

class EntryReplyCreated implements ShouldBroadcast
{
    public function __construct(public EntryReply $entryReply)
    {
    }

    public function broadcastAs(): string
    {
        return 'entryReply.created';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('entry.' . Hashids::encode($this->entryReply->parent_id));
    }
}
