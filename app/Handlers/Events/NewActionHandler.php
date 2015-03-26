<?php namespace Strimoid\Handlers\Events;

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
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $this->addHandler(Content::class, $events);
        $this->addHandler(Comment::class, $events);
        $this->addHandler(CommentReply::class, $events);
        $this->addHandler(Entry::class, $events);
        $this->addHandler(EntryReply::class, $events);
    }

    /**
     * Bind given model listener to events handler.
     *
     * @param string                        $model
     * @param \Illuminate\Events\Dispatcher $events
     */
    protected function addHandler($class, $events)
    {
        $name = 'eloquent.created: '. $class;
        $events->listen($name, self::class.'@onNewElement');
    }

    /**
     * @param BaseModel $element
     */
    public function onNewElement($element)
    {
        $action = new UserAction([
            'user_id' => $element->user_id
        ]);
        $action->element()->associate($element);
        $action->save();
    }
}
