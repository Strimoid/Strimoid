<?php

namespace Strimoid\Api\Controllers;

use Strimoid\Models\Conversation;
use Strimoid\Models\ConversationMessage;

class ConversationController extends BaseController
{
    public function getIndex()
    {
        return Conversation::with('lastMessage', 'lastMessage.user')
            ->withUser(auth()->id())
            ->get()
            ->sortBy(fn ($conversation) => $conversation->lastMessage->created_at)->reverse();
    }

    public function getMessages()
    {
        $ids = Conversation::withUser(auth()->id())->pluck('id');

        return ConversationMessage::with('conversation')->with('user')
            ->whereIn('conversation_id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    }
}
