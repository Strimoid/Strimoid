<?php

namespace Strimoid\Http\Controllers\Auth;

use Cache;
use Illuminate\Http\Request;
use Mail;
use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\User;

class RegistrationController extends BaseController
{
    public function showRegisterForm()
    {
        return view('user.register');
    }

    public function processRegistration(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:2|max:30|unique:users,name|regex:/^[a-zA-Z0-9_]+$/i',
            'password' => 'required|min:6',
            'email' => 'required|email|unique_email:users|real_email',
        ]);

        $ipHash = md5($request->getClientIp());

        if (Cache::has('registration.' . $ipHash)) {
            abort(500);
        }

        $email = $request->input('email');

        $user = new User();
        $user->name = $request->input('username');
        $user->password = $request->input('password');
        $user->email = $email;
        $user->last_ip = $request->getClientIp();
        $user->activation_token = str_random(16);
        $user->save();

        Mail::send('emails.auth.activate', compact('user'), function ($message) use ($user, $email): void {
            $message->to($email, $user->name)->subject('Witaj na Strimoid.pl!');
        });

        return redirect()->to('')->with(
            'success_msg',
            'Aby zakończyć rejestrację musisz jeszcze aktywować swoje konto, ' .
            'klikając na link przesłany na twój adres email.'
        );
    }

    public function activateAccount(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $ipHash = md5($request->getClientIp());
        if (Cache::has('registration.' . $ipHash)) {
            abort(500);
        }

        $user->is_activated = true;
        $user->save();

        auth()->login($user);

        Cache::put('registration.' . $ipHash, 'true', now()->addWeek());

        return redirect()->to('/kreator')->with(
            'success_msg',
            'Witaj w gronie użytkowników serwisu ' . config('app.site_name') . '! ;) ' .
            'Zacznij od zasubskrybowania dowolnej ilości grup, pasujących do twoich zainteresowań.'
        );
    }
}
