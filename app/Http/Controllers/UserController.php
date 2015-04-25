<?php namespace Strimoid\Http\Controllers;

use App;
use Auth;
use Cache;
use Carbon;
use Config;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Input;
use Mail;
use PDP;
use Redirect;
use Response;
use Str;
use Strimoid\Contracts\Repositories\UserRepository;
use Strimoid\Models\CommentReply;
use Strimoid\Models\EntryReply;
use Strimoid\Models\GroupModerator;
use Strimoid\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use URL;

class UserController extends BaseController
{
    use ValidatesRequests;

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

        return Response::json($users);
    }

    public function login(Request $request)
    {
        $result = Auth::attempt([
            'name'         => $request->input('username'),
            'password'     => $request->input('password'),
            'is_activated' => true,
        ], $request->input('remember') == 'true');

        if ($result) {
            if (Auth::user()->removed_at || Auth::user()->blocked_at) {
                Auth::logout();

                return Redirect::to('/login')->with('warning_msg', 'Błędna nazwa użytkownika lub hasło.');
            }

            $url = URL::previous() ?: '/';

            return Redirect::intended($url);
        }

        return Redirect::to('/login')->with('warning_msg', 'Błędna nazwa użytkownika lub hasło.');
    }

    public function logout()
    {
        Auth::logout();

        return Redirect::to('')->with('success_msg', 'Zostałeś wylogowany.');
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password'     => 'required|confirmed|min:6',
            'old_password' => 'required|user_password',
        ]);

        Auth::user()->password = $request->get('password');
        Auth::user()->save();

        return Redirect::action('UserController@showSettings')
            ->with('success_msg', 'Hasło zostało zmienione.');
    }

    public function changeEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique_email:users|real_email',
        ]);

        $email = Str::lower(Input::get('email'));

        Auth::user()->new_email = $email;
        Auth::user()->email_change_token = Str::random(16);

        Auth::user()->save();

        Mail::send('emails.auth.email_change', ['user' => Auth::user()], function ($message) use ($email) {
            $message->to($email, Auth::user()->name)->subject('Potwierdź zmianę adresu email');
        });

        return Redirect::action('UserController@showSettings')
            ->with('success_msg', 'Na podany adres email został wysłany link umożliwiający potwierdzenie zmiany.');
    }

    public function confirmEmailChange($token)
    {
        if ($token !== Auth::user()->email_change_token) {
            return Redirect::to('')->with('danger_msg', 'Błędny token.');
        }

        Auth::user()->email = Auth::user()->new_email;
        Auth::user()->unset(['email_change_token', 'new_email']);
        Auth::user()->save();

        return Redirect::to('')->with('success_msg', 'Adres email został zmieniony.');
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
                $cacheKey = 'registration.'.md5($request->getClientIp());

                if (Cache::has($cacheKey)) {
                    return App::abort(500);
                }

                $user->unset('activation_token');
                $user->is_activated = true;
            }

            $user->password = bcrypt($password);
            $user->save();
            $this->auth->login($user);
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

    public function showRegisterForm()
    {
        return view('user.register');
    }

    public function processRegistration(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:2|max:30|unique:users,name|regex:/^[a-zA-Z0-9_]+$/i',
            'password' => 'required|min:6',
            'email'    => 'required|email|unique_email:users|real_email',
        ]);

        $ipHash = md5($request->getClientIp());

        if (Cache::has('registration.'.$ipHash)) {
            return App::abort(500);
        }

        $email = $request->input('email');

        $user = new User();
        $user->name     = $request->input('username');
        $user->password = $request->input('password');
        $user->email    = $email;
        $user->last_ip  = $request->getClientIp();
        $user->save();

        Mail::send('emails.auth.activate', compact('user'), function ($message) use ($user, $email) {
            $message->to($email, $user->name)->subject('Witaj na Strimoid.pl!');
        });

        return Redirect::to('')->with(
            'success_msg',
            'Aby zakończyć rejestrację musisz jeszcze aktywować swoje konto, klikając na link przesłany na twój adres email.'
        );
    }

    public function activateAccount(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $ipHash = md5($request->getClientIp());
        if (Cache::has('registration.'.$ipHash)) {
            return App::abort(500);
        }

        $user->is_activated = true;
        $user->save();

        Auth::login($user);

        Cache::put('registration.'.$ipHash, 'true', 60 * 24 * 7);

        return Redirect::to('/kreator')->with(
            'success_msg',
            'Witaj w gronie użytkowników serwisu '.Config::get('app.site_name').'! ;) '.
            'Zacznij od zasubskrybowania dowolnej ilości grup, pasujących do twoich zainteresowań.'
        );
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

        Auth::user()->removed_at = new Carbon();
        Auth::user()->type = 'deleted';

        Auth::user()->save();

        Auth::logout();

        return Redirect::to('')->with('success_msg', 'Twoje konto zostało usunięte.');
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
            App::abort(404, 'Użytkownik usunął konto.');
        }

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

        $user = Auth::user();

        $user->sex = Input::get('sex');
        $user->age = (int) Input::get('age');
        $user->location = Input::get('location');
        $user->description = Input::get('description');

        if (Input::hasFile('avatar')) {
            $user->setAvatar(Input::file('avatar')->getRealPath());
        }

        $user->save();

        return Redirect::route('user_settings')->with('success_msg', 'Zmiany zostały zapisane.');
    }

    public function blockUser($user)
    {
        Auth::user()->blockedUsers()->attach($user);
        return Response::json(['status' => 'ok']);
    }

    public function unblockUser($user)
    {
        Auth::user()->blockedUsers()->detach($user);
        return Response::json(['status' => 'ok']);
    }

    public function observeUser($user)
    {
        Auth::user()->followedUsers()->attach($user);
        return Response::json(['status' => 'ok']);
    }

    public function unobserveUser($user)
    {
        Auth::user()->followedUsers()->detach($user);
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

        Auth::user()->push('_blocked_domains', $domain, true);

        return Response::json(['status' => 'ok', 'domain' => $domain]);
    }

    public function unblockDomain($domain)
    {
        Auth::user()->pull('_blocked_domains', $domain);

        return Response::json(['status' => 'ok']);
    }

    public function show($username)
    {
        $user = User::name($username)->firstOrFail();

        return $this->getInfo($user);
    }

    public function showCurrentUser()
    {
        $user = Auth::user();

        $info = $this->getInfo($user);

        $info['subscribed_groups'] = Auth::user()->subscribedGroups();
        $info['blocked_groups'] = Auth::user()->blockedGroups();
        $info['moderated_groups'] = Auth::user()->moderatedGroups();

        $info['folders'] = Auth::user()->folders->toArray();

        return $info;
    }

    public function getInfo($user)
    {
        $stats = [
            'contents'         => (int) $user->contents->count(),
            'comments'         => (int) $user->comments->count(),
            'entries'          => (int) $user->entries->count(),
            'moderated_groups' => intval(GroupModerator::where('user_id', $user->getKey())->count()),
        ];

        return [
            'name'        => $user->name,
            'age'         => $user->age,
            'avatar'      => $user->avatar,
            'description' => $user->description,
            'joined'      => current($user->created_at),
            'location'    => $user->location,
            'sex'         => $user->sex,
            'stats'       => $stats,
        ];
    }
}
