<?php

class AuthController extends BaseController {

    public function login()
    {
        $remember = Input::get('remember') == 'true' ? true : false;

        if (Auth::attempt(['shadow_name' => Str::lower(Input::get('username')),
            'password' => Input::get('password'), 'is_activated' => true], $remember))
        {
            if (Auth::user()->removed_at || Auth::user()->blocked_at)
            {
                Auth::logout();
                return Response::json(['error' => 'Account blocked or removed'], 400);
            }

            return Response::json(['user' => Auth::user()]);
        }

        return Response::json(['error' => 'Invalid login or password'], 400);
    }

    public function logout()
    {
        Auth::logout();
    }

}