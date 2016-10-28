<?php namespace Strimoid\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Vinkla\Pusher\PusherManager;

class AuthController extends BaseController
{
    public function showLoginForm() : View
    {
        return view('user.login');
    }

    public function login(Request $request) : RedirectResponse
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

    public function logout() : RedirectResponse
    {
        auth()->logout();

        return redirect('')->with('success_msg', trans('auth.logged_out'));
    }

    /**
     * Generate Pusher authentication token for currently logged user.
     */
    public function authenticatePusher(Request $request, PusherManager $pusher) : string
    {
        $channelName = 'privateU'.auth()->id();
        $socketId = $request->input('socket_id');

        $pusher->connection();

        return $pusher->socket_auth($channelName, $socketId);
    }


    /**
     * Redirect the user to the SOCIAL authentication page.
     *
     * @param $social
     * @return Response
     */
    public function redirectToProvider($social)
    {
        return Socialite::driver($social)->redirect();
    }

    /**
     * Obtain the user information from FACEBOOK.
     *
     * @return Response
     */
    public function handleProviderCallback($social)
    {
        try {
            $user = Socialite::driver($social)->user();
        } catch (Exception $e) {
            return redirect('auth/'.$social);
        }

        $authUser = $this->findOrCreateUser($user, $social);

        Auth::login($authUser, true);

        return redirect()->intended('');
    }



    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($User, $social)
    {
        $authUser = User::where('emai', $User->email)->first();

        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name' => $User->name,
            'email' => $User->email,
            'avatar' => $User->avatar
        ]);
    }
}
