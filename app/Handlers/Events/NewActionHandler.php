<?php namespace Strimoid\Handlers\Events;

use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\UserAction;

/**
 * Create new UserAction when new entity is created.
 */
class NewActionHandler
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        /*
        $this->addHandler('Content', $events);
        $this->addHandler('Comment', $events);
        $this->addHandler('CommentReply', $events);
        $this->addHandler('Entry', $events);
        $this->addHandler('EntryReply', $events);
        */
    }

    /**
     * Bind given model listener to events handler.
     *
     * @param string                        $model
     * @param \Illuminate\Events\Dispatcher $events
     */
    protected function addHandler($model, $events)
    {
        $name = 'eloquent.created: Strimoid\\Models\\'.$model;
        $events->listen($name, self::class.'@onNew'.$model);
    }

    /**
     * @param Content $content
     */
    public function onNewContent($content)
    {
        UserAction::create([
            'user_id'      => $content->user->getKey(),
            'type'         => UserAction::TYPE_CONTENT,
            'content_id'   => $content->getKey(),
        ]);
    }

    /**
     * @param Comment $comment
     */
    public function onNewComment($comment)
    {
        UserAction::create([
            'user_id'      => $comment->user->getKey(),
            'type'         => UserAction::TYPE_COMMENT,
            'comment_id'   => $comment->getKey(),
        ]);
    }

    /**
     * @param CommentReply $reply
     */
    public function onNewCommentReply($reply)
    {
        UserAction::create([
            'user_id'          => $reply->user->getKey(),
            'type'             => UserAction::TYPE_COMMENT_REPLY,
            'comment_reply_id' => $reply->getKey(),
        ]);
    }

    /**
     * @param Entry $entry
     */
    public function onNewEntry($entry)
    {
        UserAction::create([
            'user_id'      => $entry->user->getKey(),
            'type'         => UserAction::TYPE_ENTRY,
            'entry_id'     => $entry->getKey(),
        ]);
    }

    /**
     * @param EntryReply $reply
     */
    public function onNewEntryReply($reply)
    {
        UserAction::create([
            'user_id'        => $reply->user->getKey(),
            'type'           => UserAction::TYPE_ENTRY_REPLY,
            'entry_reply_id' => $reply->getKey(),
        ]);
    }
}
