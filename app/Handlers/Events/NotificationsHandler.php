<?php namespace Strimoid\Handlers\Events;

use Closure;
use Str;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Notification;
use Strimoid\Models\User;

/**
 * Create new Notification when user
 * was mentioned in new entity.
 */
class NotificationsHandler
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $this->addHandlers('Comment', $events);
        $this->addHandlers('CommentReply', $events);
        $this->addHandlers('Entry', $events);
        $this->addHandlers('EntryReply', $events);
    }

    /**
     * @param string                        $model
     * @param \Illuminate\Events\Dispatcher $events
     */
    protected function addHandlers($model, $events)
    {
        $created = 'eloquent.created: Strimoid\\Models\\'.$model;
        $events->listen($created, self::class.'@on'.$model.'Create');

        $updated = 'eloquent.updated: Strimoid\\Models\\'.$model;
        $events->listen($updated, self::class.'@on'.$model.'Edit');
    }

    /**
     * @param Comment $comment
     */
    public function onCommentCreate($comment)
    {
        $this->sendNotifications($comment->text_source, 'comment',
            function ($notification) use ($comment) {
                $notification->setTitle($comment->text);
                $notification->content()->associate($comment->content);
                $notification->comment()->associate($comment);
            }, $comment->user);
    }

    /**
     * @param Comment $comment
     */
    public function onCommentEdit($comment)
    {
        $comment->deleteNotifications();
        $this->onCommentCreate($comment);
    }

    /**
     * @param CommentReply $comment
     */
    public function onCommentReplyCreate($comment)
    {
        $this->sendNotifications($comment->text_source, 'comment_reply',
            function ($notification) use ($comment) {
                $parent = $comment->getParentRelation()->getParent();

                $notification->setTitle($comment->text);
                $notification->content()->associate($parent->content);
                $notification->commentReply()->associate($comment);
            }, $comment->user);
    }

    /**
     * @param CommentReply $comment
     */
    public function onCommentReplyEdit($comment)
    {
        $comment->deleteNotifications();
        $this->onCommentReplyCreate($comment);
    }

    /**
     * @param Entry $entry
     */
    public function onEntryCreate($entry)
    {
        $this->sendNotifications($entry->text_source, 'entry',
            function ($notification) use ($entry) {
                $notification->setTitle($entry->text);
                $notification->entry()->associate($entry);
            }, $entry->user);
    }

    /**
     * @param Entry $entry
     */
    public function onEntryEdit($entry)
    {
        $entry->deleteNotifications();
        $this->onEntryCreate($entry);
    }

    /**
     * @param EntryReply $entry
     */
    public function onEntryReplyCreate($entry)
    {
        $this->sendNotifications($entry->text_source, 'entry_reply',
            function ($notification) use ($entry) {
                $notification->setTitle($entry->text);
                $notification->entryReply()->associate($entry);
            }, $entry->user);
    }

    /**
     * @param EntryReply $entry
     */
    public function onEntryReplyEdit($entry)
    {
        $entry->deleteNotifications();
        $this->onEntryReplyCreate($entry);
    }

    /**
     * Send notifications to given users.
     *
     * @param array|string $targets
     * @param string       $type
     * @param Closure      $callback
     * @param User         $sourceUser
     */
    protected function sendNotifications($targets, $type,
        Closure $callback, User $sourceUser)
    {
        $uniqueUsers = is_array($targets)
            ? $targets
            : $this->findMentions($targets);

        if (! $uniqueUsers) return;

        $notification = new Notification();
        $notification->type = $type;
        $notification->sourceUser()->associate($sourceUser);

        foreach ($uniqueUsers as $uniqueUser) {
            $user = User::name($uniqueUser)->first();

            if ($user && $user->getKey() != $sourceUser->getKey()
                && ! $user->isBlockingUser($sourceUser)) {
                $notification->addTarget($user);
            }
        }

        $callback($notification);
        $notification->save();
    }

    /**
     * Get list of users mentioned in given text.
     *
     * @param string $text Source text
     *
     * @return array
     */
    protected function findMentions($text)
    {
        preg_match_all('/@([a-z0-9_-]+)/i', $text, $users, PREG_SET_ORDER);

        $uniqueUsers = [];

        foreach ($users as $user) {
            if (! isset($user[1])) {
                break;
            }

            $lowercase = Str::lower($user[1]);
            if (in_array($lowercase, $uniqueUsers)) {
                break;
            }
            $uniqueUsers[] = $lowercase;
        }

        return $uniqueUsers;
    }
}
