<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Pusher\Laravel\PusherManager;

class AuthController extends BaseController
{
    public function showLoginForm(): View
    {
        return view('user.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $result = auth()->attempt([
            'name' => $request->input('username'),
            'password' => $request->input('password'),
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

    public function logout(): RedirectResponse
    {
        auth()->logout();

        return redirect('')->with('success_msg', trans('auth.logged_out'));
    }

    public function authenticatePusher(Request $request, PusherManager $pusher): string
    {
        $channelName = 'privateU' . auth()->id();
        $socketId = $request->input('socket_id');

        $pusher->connection();

        return $pusher->socket_auth($channelName, $socketId);
    }
}
