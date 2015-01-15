<?php namespace Strimoid\Handlers\Events; 

use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\UserAction;

class NewActionHandler {

    public function onNewContent(Content $content)
    {
        UserAction::create([
            'user_id'      => $content->user->getKey(),
            'type'         => UserAction::TYPE_CONTENT,
            'content_id'   => $content->getKey()
        ]);
    }

    public function onNewComment(Comment $comment)
    {
        UserAction::create([
            'user_id'      => $comment->user->getKey(),
            'type'         => UserAction::TYPE_COMMENT,
            'comment_id'   => $comment->getKey()
        ]);
    }

    public function onNewCommentReply(CommentReply $reply)
    {
        UserAction::create([
            'user_id'          => $reply->user->getKey(),
            'type'             => UserAction::TYPE_COMMENT_REPLY,
            'comment_reply_id' => $reply->getKey()
        ]);
    }

    public function onNewEntry(Entry $entry)
    {
        UserAction::create([
            'user_id'      => $entry->user->getKey(),
            'type'         => UserAction::TYPE_ENTRY,
            'entry_id'     => $entry->getKey()
        ]);
    }

    public function onNewEntryReply(EntryReply $reply)
    {
        UserAction::create([
            'user_id'        => $reply->user->getKey(),
            'type'           => UserAction::TYPE_ENTRY_REPLY,
            'entry_reply_id' => $reply->getKey()
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $this->addHandler('Content', $events);
        $this->addHandler('Comment', $events);
        $this->addHandler('CommentReply', $events);
        $this->addHandler('Entry', $events);
        $this->addHandler('EntryReply', $events);
    }

    private function addHandler($modelName, $events)
    {
        $events->listen('eloquent.created: Strimoid\\Models\\'. $modelName,
            'Strimoid\\Handlers\\Events\\NewActionHandler@onNew'. $modelName);
    }

}
