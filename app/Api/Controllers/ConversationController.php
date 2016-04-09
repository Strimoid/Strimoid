<?php namespace Strimoid\Api\Controllers;

use Strimoid\Models\Conversation;
use Strimoid\Models\ConversationMessage;

class ConversationController extends BaseController
{
    public function getIndex()
    {
        $conversations = Conversation::with('lastMessage', 'lastMessage.user')
            ->withUser(auth()->id())
            ->get()
            ->sortBy(function ($conversation) {
                return $conversation->lastMessage->created_at;
            })->reverse();

        return $conversations;
    }

    public function getMessages()
    {
        $ids = Conversation::withUser(auth()->id())->lists('id');

        $messages = ConversationMessage::with('conversation')->with('user')
            ->whereIn('conversation_id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return $messages;
    }
}
