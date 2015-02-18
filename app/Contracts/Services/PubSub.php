<?php namespace Strimoid\Contracts\Services;

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
     * @param  Closure $callback
     * @return void
     */
    public function subscribe($channel, Closure $callback);

}
