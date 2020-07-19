<?php

namespace Strimoid\Entry\Events;

use Illuminate\Broadcasting\Channel;
use Strimoid\Models\EntryReply;

class EntryReplyCreated
{
    public EntryReply $entryReply;

    public function __construct(EntryReply $entryReply)
    {
        $this->entryReply = $entryReply;
    }

    public function broadcastAs(): string
    {
        return 'entryReply.created';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('entry.' . $this->entryReply->hashId());
    }
}
