<?php

namespace Strimoid\Handlers\Events;

use Illuminate\Events\Dispatcher;
use Strimoid\Models\BaseModel;
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
    public function subscribe(Dispatcher $events): void
    {
        $this->addHandler(Content::class, $events);
        $this->addHandler(Comment::class, $events);
        $this->addHandler(CommentReply::class, $events);
        $this->addHandler(Entry::class, $events);
        $this->addHandler(EntryReply::class, $events);
    }

    protected function addHandler($class, Dispatcher $events): void
    {
        $name = 'eloquent.created: ' . $class;
        $events->listen($name, self::class . '@onNewElement');
    }

    public function onNewElement(BaseModel $element): void
    {
        $action = new UserAction([
            'user_id' => $element->user_id,
        ]);
        $action->element()->associate($element);
        $action->save();
    }
}
