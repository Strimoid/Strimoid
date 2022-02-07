<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends BaseController
{
    public function __construct(
        private \Illuminate\Contracts\View\Factory $viewFactory,
        private \Illuminate\Contracts\Auth\Guard $guard,
        private \Illuminate\Routing\Redirector $redirector
    ) {
    }

    public function showLoginForm(): View
    {
        return $this->viewFactory->make('user.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $result = $this->guard->attempt([
            'name' => $request->input('username'),
            'password' => $request->input('password'),
            'is_activated' => true,
        ], $request->input('remember') === 'true');

        if ($result) {
            if ($this->guard->user()->removed_at || $this->guard->user()->blocked_at) {
                $this->guard->logout();

                return $this->redirector->away('/login')->with('warning_msg', __('auth.invalid_credentials'));
            }

            return $this->redirector->intended('');
        }

        return $this->redirector->away('/login')->with('warning_msg', __('auth.invalid_credentials'));
    }

    public function logout(): RedirectResponse
    {
        $this->guard->logout();

        return $this->redirector->back()->with('success_msg', __('auth.logged_out'));
    }
}
