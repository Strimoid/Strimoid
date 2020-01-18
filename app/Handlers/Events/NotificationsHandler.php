<?php

namespace Strimoid\Handlers\Events;

use Closure;
use Illuminate\Events\Dispatcher;
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
     */
    public function subscribe(Dispatcher $events): void
    {
        $this->addHandlers(Comment::class, $events);
        $this->addHandlers(CommentReply::class, $events);
        $this->addHandlers(Entry::class, $events);
        $this->addHandlers(EntryReply::class, $events);
        $this->addHandlers(ConversationMessage::class, $events);
    }

    protected function addHandlers(string $class, Dispatcher $events): void
    {
        $baseName = class_basename($class);

        $created = 'eloquent.created: ' . $class;
        $events->listen($created, self::class . '@on' . $baseName . 'Create');

        $updated = 'eloquent.updated: ' . $class;
        $events->listen($updated, self::class . '@on' . $baseName . 'Edit');
    }

    public function onCommentCreate(Comment $comment): void
    {
        $this->sendNotifications(
            $comment->text_source,
            function (Notification $notification) use ($comment): void {
                $notification->setTitle($comment->text);
                $notification->element()->associate($comment);
            },
            $comment->user
        );
    }

    public function onCommentEdit(Comment $comment): void
    {
        $notification = $comment->notifications()->first();
        $this->updateNotificationTargets($notification, $comment->text_source);
    }

    public function onCommentReplyCreate(CommentReply $comment): void
    {
        $this->sendNotifications(
            $comment->text_source,
            function (Notification $notification) use ($comment): void {
                $notification->setTitle($comment->text);
                $notification->element()->associate($comment);
            },
            $comment->user
        );
    }

    public function onCommentReplyEdit(CommentReply $comment): void
    {
        $notification = $comment->notifications()->first();
        $this->updateNotificationTargets($notification, $comment->text_source);
    }

    public function onEntryCreate(Entry $entry): void
    {
        $this->sendNotifications(
            $entry->text_source,
            function (Notification $notification) use ($entry): void {
                $notification->setTitle($entry->text);
                $notification->element()->associate($entry);
            },
            $entry->user
        );
    }

    public function onEntryEdit(Entry $entry): void
    {
        $notification = $entry->notifications()->first();
        $this->updateNotificationTargets($notification, $entry->text_source);
    }

    public function onEntryReplyCreate(EntryReply $entry): void
    {
        $this->sendNotifications(
            $entry->text_source,
            function (Notification $notification) use ($entry): void {
                $notification->setTitle($entry->text);
                $notification->element()->associate($entry);
            },
            $entry->user
        );
    }

    public function onEntryReplyEdit(EntryReply $entry): void
    {
        $notification = $entry->notifications()->first();
        $this->updateNotificationTargets($notification, $entry->text_source);
    }

    public function onConversationMessageCreate(ConversationMessage $message): void
    {
        $conversation = $message->conversation;
        $targets = $conversation->users;

        $this->sendNotifications(
            $targets->all(),
            function (Notification $notification) use ($message): void {
                $notification->setTitle($message->text);
                $notification->element()->associate($message->conversation);
            },
            $message->user
        );
    }

    /**
     * Find mention differences and update related notification targets.
     */
    protected function updateNotificationTargets(Notification $notification, string $newText): void
    {
        $oldUserIds = $notification->targets()->pluck('user_id')->toArray();
        $newUsers = $this->findMentionedUsers($newText);

        $newTargets = $newUsers->filter(fn(User $user) => !in_array($user->getKey(), $oldUserIds));

        $removedTargets = array_diff($oldUserIds, $newUsers->pluck('id')->toArray());

        if (count($removedTargets) > 0) {
            foreach ($removedTargets as $removedTarget) {
                $notification->targets()->detach($removedTarget);
            }
        }
    }

    /**
     * Send notifications to given users.
     *
     * @param array|string $targets
     */
    protected function sendNotifications($targets, Closure $callback, User $sourceUser): void
    {
        $users = is_array($targets)
            ? $targets
            : $this->findMentionedUsers($targets);

        $notification = new Notification();
        $notification->user()->associate($sourceUser);
        $callback($notification);
        $this->addPushTargets($notification, $users);
        $notification->save();
        $this->addTargets($notification, $users);
    }

    /**
     * Add users as targets of push notification.
     */
    protected function addPushTargets(Notification $notification, $users): void
    {
        $sourceUser = $notification->user;

        foreach ($users as $targetUser) {
            if ($this->isNotMyselfOrBlockedByReceiver($sourceUser, $targetUser)) {
                $notification->targets->add($targetUser);
            }
        }
    }

    /**
     * Add users as targets of notification.
     */
    protected function addTargets(Notification $notification, array $users): void
    {
        $sourceUser = $notification->user;
        foreach ($users as $targetUser) {
            if ($this->isNotMyselfOrBlockedByReceiver($sourceUser, $targetUser)) {
                $notification->targets()->attach($targetUser);
            }
        }
    }

    /**
     * Checks that user is not "myself" or is not blocked by notification target user.
     */
    public function isNotMyselfOrBlockedByReceiver(User $sourceUser, User $targetUser)
    {
        if ($targetUser->getKey() != $sourceUser->getKey() && !$targetUser->isBlockingUser($sourceUser)) {
            return true;
        }

        return false;
    }

    /**
     * Get list of users mentioned in given text.
     */
    protected function findMentionedUsers(string $text): array
    {
        preg_match_all('/@([a-z0-9_-]+)/i', $text, $matches, PREG_SET_ORDER);
        $nicknames = array_pluck($matches, 1);

        return User::whereIn('name', $nicknames)->get()->all();
    }
}
