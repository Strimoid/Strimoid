<?php namespace Strimoid\Http\Controllers\Auth;

use Illuminate\Http\Response;
use Socialite;
use Strimoid\Http\Controllers\BaseController;

class LoginController extends BaseController
{
    public function redirectToProvider(string $driver): Response
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback(string $driver): Response
    {
        $user = Socialite::driver($driver)->user();


    }
}
