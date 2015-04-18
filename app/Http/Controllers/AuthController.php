<?php namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Vinkla\Pusher\PusherManager;

class AuthController extends BaseController
{
    /**
     * Generate Pusher authentication token for currently logged user.
     *
     * @param  Request $request
     * @param  PusherManager $pusher
     *
     * @return string
     */
    public function authenticatePusher(Request $request, PusherManager $pusher)
    {
        $channelName = 'private-u-'.Auth::id();
        $socketId = $request->input('socket_id');

        $pusher->connection();

        return $pusher->socket_auth($channelName, $socketId);
    }
}
