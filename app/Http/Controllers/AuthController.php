<?php namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Pusher\Facades\Pusher;

class AuthController extends BaseController {
    /**
     * Generate Pusher authentication token for currently logged user.
     *
     * @param  Request  $request
     */
    public function authenticatePusher(Request $request)
    {
        $channelName = 'private-u-'.Auth::id();
        $socketId = $request->input('socket_id');

        return Pusher::socket_auth($channelName, $socketId);
    }
}
