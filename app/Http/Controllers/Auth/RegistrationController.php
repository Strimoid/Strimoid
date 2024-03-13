<?php

namespace Strimoid\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\User;

class RegistrationController extends BaseController
{
    public function __construct(private readonly \Illuminate\Contracts\View\Factory $viewFactory, private readonly \Illuminate\Cache\CacheManager $cacheManager, private readonly \Illuminate\Mail\Mailer $mailer, private readonly \Illuminate\Routing\Redirector $redirector, private readonly \Illuminate\Contracts\Auth\Guard $guard, private readonly \Illuminate\Contracts\Config\Repository $configRepository)
    {
    }
    public function showRegisterForm()
    {
        return $this->viewFactory->make('user.register');
    }

    public function processRegistration(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:2|max:30|unique:users,name|regex:/^[a-zA-Z0-9_]+$/i',
            'password' => 'required|min:6',
            'email' => 'required|email|unique_email:users|real_email',
        ]);

        $ipHash = md5((string) $request->getClientIp());

        if ($this->cacheManager->has('registration.' . $ipHash)) {
            abort(500);
        }

        $user = new User();
        $user->name = $request->input('username');
        $user->password = $request->input('password');
        $user->email = $request->input('email');
        $user->last_ip = $request->getClientIp();
        $user->activation_token = Str::random(16);
        $user->save();

        $this->mailer->send('emails.auth.activate', compact('user'), function (Message $message) use ($user): void {
            $message->to($user->email, $user->name)->subject('Witaj na Strm.pl!');
        });

        return $this->redirector->to('')->with(
            'success_msg',
            'Aby zakończyć rejestrację musisz jeszcze aktywować swoje konto, ' .
            'klikając na link przesłany na twój adres email.'
        );
    }

    public function activateAccount(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $ipHash = md5((string) $request->getClientIp());
        if ($this->cacheManager->has('registration.' . $ipHash)) {
            abort(500);
        }

        $user->is_activated = true;
        $user->save();

        $this->guard->login($user);

        $this->cacheManager->put('registration.' . $ipHash, 'true', now()->addWeek());

        return $this->redirector->to('/kreator')->with(
            'success_msg',
            'Witaj w gronie użytkowników serwisu ' . $this->configRepository->get('app.name') . '! ;) ' .
            'Zacznij od zasubskrybowania grup pasujących do twoich zainteresowań.'
        );
    }
}
