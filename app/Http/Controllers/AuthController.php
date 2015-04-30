<?php namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Vinkla\Pusher\PusherManager;

class AuthController extends BaseController
{
    /**
     * Show login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('user.login');
    }

    /**
     * Try to log in with credentials from request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function login(Request $request)
    {
        $result = auth()->attempt([
            'name'         => $request->input('username'),
            'password'     => $request->input('password'),
            'is_activated' => true,
        ], $request->input('remember') == 'true');

        if ($result) {
            if (auth()->user()->removed_at || auth()->user()->blocked_at) {
                auth()->logout();
                return redirect('/login')->with('warning_msg', trans('auth.invalid_credentials'));
            }

            return redirect()->intended('');
        }

        return redirect('/login')->with('warning_msg', trans('auth.invalid_credentials'));
    }

    /**
     * Logout current user.
     *
     * @return mixed
     */
    public function logout()
    {
        auth()->logout();
        return redirect('')->with('success_msg', trans('auth.logged_out'));
    }

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
        $channelName = 'private-u-'.auth()->id();
        $socketId = $request->input('socket_id');

        $pusher->connection();

        return $pusher->socket_auth($channelName, $socketId);
    }
}
