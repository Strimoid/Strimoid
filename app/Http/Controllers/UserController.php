<?php

namespace Strimoid\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Strimoid\Contracts\Repositories\UserRepository;
use Strimoid\Facades\PDP;
use Strimoid\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseController
{
    public function __construct(protected UserRepository $users, protected PasswordBroker $passwords, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private \Illuminate\Routing\Redirector $redirector, private \Illuminate\Mail\Mailer $mailer, private \Illuminate\Translation\Translator $translator, private \Illuminate\Contracts\View\Factory $viewFactory, private \Illuminate\Cache\CacheManager $cacheManager, private \Illuminate\Contracts\Auth\Guard $guard)
    {
    }

    public function showJSONList(): JsonResponse
    {
        $users = [];

        foreach (User::all() as $user) {
            $users[] = [
                'value' => $user->name,
                'avatar' => $user->getAvatarPath(),
            ];
        }

        return $this->responseFactory->json($users)
            ->setPublic()
            ->setMaxAge(3600);
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'old_password' => 'required|user_password',
        ]);

        user()->password = $request->get('password');
        user()->save();

        return $this->redirector->action('SettingsController@showSettings')
            ->with('success_msg', 'Hasło zostało zmienione.');
    }

    public function changeEmail(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email|unique_email:users|real_email',
        ]);

        $email = Str::lower($request->input('email'));

        user()->new_email = $email;
        user()->email_change_token = Str::random(16);

        user()->save();

        $this->mailer->send('emails.auth.email_change', ['user' => user()], function ($message) use ($email): void {
            $message->to($email, user()->name)->subject('Potwierdź zmianę adresu email');
        });

        return $this->redirector->action('SettingsController@showSettings')
            ->with('success_msg', 'Na podany adres email został wysłany link umożliwiający potwierdzenie zmiany.');
    }

    public function confirmEmailChange($token): RedirectResponse
    {
        if ($token !== user()->email_change_token) {
            return $this->redirector->to('')->with('danger_msg', 'Błędny token.');
        }

        user()->email = user()->new_email;
        user()->unset(['email_change_token', 'new_email']);
        user()->save();

        return $this->redirector->to('')->with('success_msg', 'Adres email został zmieniony.');
    }

    public function remindPassword(Request $request)
    {
        if ($request->has('email')) {
            $this->validate($request, ['email' => 'required|email']);

            $response = $this->passwords->sendResetLink($request->only('email'));

            switch ($response) {
                case PasswordBroker::RESET_LINK_SENT:
                    return $this->redirector->back()->with('status', $this->translator->trans($response));
                case PasswordBroker::INVALID_USER:
                    return $this->redirector->back()->withErrors(['email' => $this->translator->trans($response)]);
            }
        }

        return $this->viewFactory->make('user.remind');
    }

    public function showPasswordResetForm($token = null)
    {
        if (!$token) {
            throw new NotFoundHttpException();
        }

        return $this->viewFactory->make('user.reset')->with('token', $token);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        $response = $this->passwords->reset($credentials, function ($user, $password) use($request) {
            // Email confirmed, we may activate account if user didn't that yet
            if ($user->activation_token) {
                $cacheKey = 'registration.' . md5($request->getClientIp());

                if ($this->cacheManager->has($cacheKey)) {
                    abort(500);
                }

                $user->unset('activation_token');
                $user->is_activated = true;
            }

            $user->password = $password;
            $user->save();

            $this->guard->login($user);
        });

        return match ($response) {
            PasswordBroker::PASSWORD_RESET => $this->redirector->back('/'),
            default => $this->redirector->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $this->translator->trans($response)]),
        };
    }

    public function showLoginForm()
    {
        return $this->viewFactory->make('user.login');
    }

    public function showRemoveAccountForm()
    {
        return $this->viewFactory->make('user.remove');
    }

    public function removeAccount(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|user_password',
        ]);

        user()->removed_at = Carbon::now();
        user()->type = 'deleted';

        user()->save();

        $this->guard->logout();

        return $this->redirector->to('')->with('success_msg', 'Twoje konto zostało usunięte.');
    }

    public function showProfile(User $user, string $type = 'all')
    {
        if ($user->removed_at) {
            abort(404, 'Użytkownik usunął konto.');
        }

        $data = [];

        if ($type === 'contents') {
            $data['contents'] = $user->contents()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type === 'comments') {
            $data['comments'] = $user->comments()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type === 'comment_replies') {
            $data['replies'] = $user->commentReplies()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type === 'entries') {
            $data['entries'] = $user->entries()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type === 'entry_replies') {
            $data['replies'] = $user->entryReplies()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type === 'moderated') {
            $data['moderated'] = $user->moderatedGroups()->paginate(25);
        } else {
            $data['actions'] = $user->actions()->with('element')->orderBy('created_at', 'desc')->paginate(15);
        }

        $data['type'] = $type;
        $data['user'] = $user;

        return $this->viewFactory->make('user.profile', $data);
    }

    public function saveProfile(Request $request)
    {
        $this->validate($request, [
            'sex' => 'in:male,female,unknown',
            'avatar' => 'image|max:1024',
            'age' => 'integer|min:1900|max:2020',
            'location' => 'max:32',
            'description' => 'max:250',
        ]);

        $user = user();

        $data = $request->only(['sex', 'age', 'location', 'description']);
        $user->fill($data);

        if ($request->hasFile('avatar')) {
            $user->setAvatar($request->file('avatar')->getRealPath());
        }

        $user->save();

        return $this->redirector->route('user_settings')->with('success_msg', 'Zmiany zostały zapisane.');
    }

    public function blockUser($user)
    {
        user()->blockedUsers()->attach($user);
        $this->cacheManager->tags(['user.blocked-users', 'u.' . $this->guard->id()])->flush();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function unblockUser($user)
    {
        user()->blockedUsers()->detach($user);
        $this->cacheManager->tags(['user.blocked-users', 'u.' . $this->guard->id()])->flush();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function observeUser($user)
    {
        user()->followedUsers()->attach($user);

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function unobserveUser($user)
    {
        user()->followedUsers()->detach($user);

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function blockDomain($domain)
    {
        $domain = (new Uri($domain))->getHost();
        $domain = PDP::resolve($domain)->getRegistrableDomain();

        if (!$domain) {
            return $this->responseFactory->json([
                'status' => 'error', 'error' => 'Nieprawidłowa domena',
            ]);
        }

        user()->push('_blocked_domains', $domain, true);

        return $this->responseFactory->json(['status' => 'ok', 'domain' => $domain]);
    }

    public function unblockDomain($domain)
    {
        user()->pull('_blocked_domains', $domain);

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
