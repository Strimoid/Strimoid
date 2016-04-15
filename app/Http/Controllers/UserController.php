<?php namespace Strimoid\Http\Controllers;

use Cache;
use Carbon;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Input;
use Mail;
use PDP;
use Response;
use Str;
use Strimoid\Contracts\Repositories\UserRepository;
use Strimoid\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * The password broker implementation.
     *
     * @var PasswordBroker
     */
    protected $passwords;

    /**
     * @param UserRepository $users
     * @param PasswordBroker $passwords
     */
    public function __construct(UserRepository $users, PasswordBroker $passwords)
    {
        $this->users = $users;
        $this->passwords = $passwords;
    }

    public function showJSONList()
    {
        $users = [];

        foreach (User::all() as $user) {
            $users[] = [
                'value'  => $user->name,
                'avatar' => $user->getAvatarPath(),
            ];
        }

        return $users;
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password'     => 'required|confirmed|min:6',
            'old_password' => 'required|user_password',
        ]);

        user()->password = $request->get('password');
        user()->save();

        return redirect()->action('SettingsController@showSettings')
            ->with('success_msg', 'Hasło zostało zmienione.');
    }

    public function changeEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique_email:users|real_email',
        ]);

        $email = Str::lower(request('email'));

        user()->new_email = $email;
        user()->email_change_token = Str::random(16);

        user()->save();

        Mail::send('emails.auth.email_change', ['user' => user()], function ($message) use ($email) {
            $message->to($email, user()->name)->subject('Potwierdź zmianę adresu email');
        });

        return redirect()->action('SettingsController@showSettings')
            ->with('success_msg', 'Na podany adres email został wysłany link umożliwiający potwierdzenie zmiany.');
    }

    public function confirmEmailChange($token)
    {
        if ($token !== user()->email_change_token) {
            return redirect()->to('')->with('danger_msg', 'Błędny token.');
        }

        user()->email = user()->new_email;
        user()->unset(['email_change_token', 'new_email']);
        user()->save();

        return redirect()->to('')->with('success_msg', 'Adres email został zmieniony.');
    }

    public function remindPassword(Request $request)
    {
        if (Input::has('email')) {
            $this->validate($request, ['email' => 'required|email']);

            $response = $this->passwords->sendResetLink($request->only('email'), function ($m) {
                $m->subject('Zmiana hasła w serwisie Strimoid.pl');
            });

            switch ($response) {
                case PasswordBroker::RESET_LINK_SENT:
                    return redirect()->back()->with('status', trans($response));
                case PasswordBroker::INVALID_USER:
                    return redirect()->back()->withErrors(['email' => trans($response)]);
            }
        }

        return view('user.remind');
    }

    public function showPasswordResetForm($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException();
        }

        return view('user.reset')->with('token', $token);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = $this->passwords->reset($credentials, function ($user, $password) {
            // Email confirmed, we may activate account if user didn't that yet
            if ($user->activation_token) {
                $cacheKey = 'registration.'.md5(request()->getClientIp());

                if (Cache::has($cacheKey)) {
                    return abort(500);
                }

                $user->unset('activation_token');
                $user->is_activated = true;
            }

            $user->password = $password;
            $user->save();

            auth()->login($user);
        });

        switch ($response) {
            case PasswordBroker::PASSWORD_RESET:
                return redirect('/');
            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }

    public function showLoginForm()
    {
        return view('user.login');
    }

    public function showRemoveAccountForm()
    {
        return view('user.remove');
    }

    public function removeAccount(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|user_password',
        ]);

        user()->removed_at = new Carbon();
        user()->type = 'deleted';

        user()->save();

        auth()->logout();

        return redirect()->to('')->with('success_msg', 'Twoje konto zostało usunięte.');
    }

    /**
     * Show user profile view.
     *
     * @param  User  $user
     * @param  string $type
     *
     * @return \Illuminate\View\View
     */
    public function showProfile($user, $type = 'all')
    {
        if ($user->removed_at) {
            abort(404, 'Użytkownik usunął konto.');
        }

        $data = [];

        if ($type == 'contents') {
            $data['contents'] = $user->contents()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type == 'comments') {
            $data['comments'] = $user->comments()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type == 'comment_replies') {
            $data['replies'] = $user->commentReplies()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type == 'entries') {
            $data['entries'] = $user->entries()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type == 'entry_replies') {
            $data['replies'] = $user->entryReplies()->orderBy('created_at', 'desc')->paginate(15);
        } elseif ($type == 'moderated') {
            $data['moderated'] = $user->moderatedGroups()->paginate(25);
        } else {
            $data['actions'] = $user->actions()->with('element')->orderBy('created_at', 'desc')->paginate(15);
        }

        $data['type'] = $type;
        $data['user'] = $user;

        return view('user.profile', $data);
    }

    public function saveProfile(Request $request)
    {
        $this->validate($request, [
            'sex'         => 'in:male,female',
            'avatar'      => 'image|max:1024',
            'age'         => 'integer|min:1900|max:2010',
            'location'    => 'max:32',
            'description' => 'max:250',
        ]);

        $user = user();

        $data = request()->only(['sex', 'age', 'location', 'description']);
        $user->fill($data);

        if (request()->hasFile('avatar')) {
            $user->setAvatar(request()->file('avatar')->getRealPath());
        }

        $user->save();

        return redirect()->route('user_settings')->with('success_msg', 'Zmiany zostały zapisane.');
    }

    public function blockUser($user)
    {
        user()->blockedUsers()->attach($user);
        return Response::json(['status' => 'ok']);
    }

    public function unblockUser($user)
    {
        user()->blockedUsers()->detach($user);
        return Response::json(['status' => 'ok']);
    }

    public function observeUser($user)
    {
        user()->followedUsers()->attach($user);
        return Response::json(['status' => 'ok']);
    }

    public function unobserveUser($user)
    {
        user()->followedUsers()->detach($user);
        return Response::json(['status' => 'ok']);
    }

    public function blockDomain($domain)
    {
        $domain = PDP::parseUrl($domain)->host->registerableDomain;

        if (! $domain) {
            return Response::json([
                'status' => 'error', 'error' => 'Nieprawidłowa domena',
            ]);
        }

        user()->push('_blocked_domains', $domain, true);

        return Response::json(['status' => 'ok', 'domain' => $domain]);
    }

    public function unblockDomain($domain)
    {
        user()->pull('_blocked_domains', $domain);

        return Response::json(['status' => 'ok']);
    }
}
