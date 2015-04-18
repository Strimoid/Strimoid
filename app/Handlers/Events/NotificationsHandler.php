<?php namespace Strimoid\Handlers\Events;

use Closure;
use Str;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\ConversationMessage;
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
        $this->addHandlers(Comment::class, $events);
        $this->addHandlers(CommentReply::class, $events);
        $this->addHandlers(Entry::class, $events);
        $this->addHandlers(EntryReply::class, $events);
    }

    /**
     * @param string $class
     * @param \Illuminate\Events\Dispatcher $events
     */
    protected function addHandlers($class, $events)
    {
        $baseName = class_basename($class);

        $created = 'eloquent.created: '.$class;
        $events->listen($created, self::class.'@on'.$baseName.'Create');

        $updated = 'eloquent.updated: '.$class;
        $events->listen($updated, self::class.'@on'.$baseName.'Edit');
    }

    /**
     * @param Comment $comment
     */
    public function onCommentCreate($comment)
    {
        $this->sendNotifications(
            $comment->text_source,
            function (Notification $notification) use ($comment) {
                $notification->setTitle($comment->text);
                $notification->element()->associate($comment);
            },
            $comment->user
        );
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
        $this->sendNotifications(
            $comment->text_source,
            function (Notification $notification) use ($comment) {
                $notification->setTitle($comment->text);
                $notification->element()->associate($comment);
            },
            $comment->user
        );
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
        $this->sendNotifications(
            $entry->text_source,
            function (Notification $notification) use ($entry) {
                $notification->setTitle($entry->text);
                $notification->element()->associate($entry);
            },
            $entry->user
        );
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
        $this->sendNotifications(
            $entry->text_source,
            function (Notification $notification) use ($entry) {
                $notification->setTitle($entry->text);
                $notification->element()->associate($entry);
            },
            $entry->user
        );
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
     * @param ConversationMessage $message
     */
    public function onConversationMessageCreate($message)
    {
        $conversation = $message->conversation;
        $targets = $conversation->users;

        $this->sendNotifications(
            $targets,
            function (Notification $notification) use ($message) {
                $notification->setTitle($message->text);
                $notification->element()->associate($message->conversation);
            },
            $message->user
        );

        $conversation->notifications()->whereIn('user_id', $targets)->delete();
    }

    /**
     * Send notifications to given users.
     *
     * @param array|string $targets
     * @param Closure      $callback
     * @param User         $sourceUser
     */
    protected function sendNotifications($targets, Closure $callback, User $sourceUser)
    {
        $uniqueUsers = is_array($targets)
            ? $targets
            : $this->findMentions($targets);

        if (! $uniqueUsers) {
            return;
        }

        $notification = new Notification();
        $notification->user()->associate($sourceUser);

        $callback($notification);

        $notification->save();

        foreach ($uniqueUsers as $uniqueUser) {
            $user = User::name($uniqueUser)->first();

            if ($user && $user->getKey() != $sourceUser->getKey()
                && ! $user->isBlockingUser($sourceUser)) {
                $notification->targets()->attach($user. ['read' => false]);
            }
        }
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
