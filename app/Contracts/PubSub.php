<?php namespace Strimoid\Contracts; 

use Closure;

interface PubSub {

    /**
     * Publish new message on given channel.
     *
     * @param  $channel
     * @param  $message
     * @return void
     */
    public function publish($channel, $message);

    /**
     * Subscribe to given channel.
     *
     * @param  $channel
     * @param  callable $callback
     * @return void
     */
    public function subscribe($channel, Closure $callback);

}
