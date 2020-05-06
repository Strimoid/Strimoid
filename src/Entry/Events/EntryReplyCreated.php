<?php

namespace Strimoid\Entry\Events;

use Strimoid\Models\EntryReply;

class EntryReplyCreated
{
    private EntryReply $entryReply;

    public function __construct(EntryReply $entryReply)
    {
        $this->entryReply = $entryReply;
    }


}
