<?php namespace Strimoid\Http\Controllers;

class UserController extends BaseController {

    public function showJSONList()
    {
        $users = array();

        foreach(User::all() as $user) {
            $users[] = array(
                'value' => $user->name,
                'avatar' => $user->getAvatarPath()
            );
        }

        return Response::json($users);
    }

    public function login()
    {
        $remember = Input::get('remember') == 'true' ? true : false;

        if (Auth::attempt(['shadow_name' => Str::lower(Input::get('username')),
            'password' => Input::get('password'), 'is_activated' => true], $remember))
        {
            if (Auth::user()->removed_at || Auth::user()->blocked_at)
            {
                Auth::logout();
                return Redirect::to('/login')->with('warning_msg', 'Błędna nazwa użytkownika lub hasło.');
            }

            $url = URL::previous() ? URL::previous() : '';

            return Redirect::intended($url);
        }

        return Redirect::to('/login')->with('warning_msg', 'Błędna nazwa użytkownika lub hasło.');
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('')->with('success_msg', 'Zostałeś wylogowany.');
    }

    public function changePassword()
    {
        $validator = Validator::make(Input::all(), [
            'password' => 'required|confirmed|min:6',
            'old_password' => 'required|user_password'
        ]);

        if ($validator->fails())
        {
            return Redirect::action('UserController@showSettings')->withErrors($validator);
        }

        Auth::user()->password = Input::get('password');
        Auth::user()->save();

        return Redirect::action('UserController@showSettings')->with('success_msg', 'Hasło zostało zmienione.');
    }

    public function changeEmail()
    {
        $validator = Validator::make(Input::all(), [
            'email' => 'required|email|unique_email:users|real_email'
        ]);

        if ($validator->fails())
        {
            return Redirect::action('UserController@showSettings')->withErrors($validator);
        }

        $email = Str::lower(Input::get('email'));

        Auth::user()->new_email = $email;
        Auth::user()->email_change_token = Str::random(16);

        Auth::user()->save();

        Mail::send('emails.auth.email_change', ['user' => Auth::user()], function($message) use($email)
        {
            $message->to($email, Auth::user()->name)->subject('Potwierdź zmianę adresu email');
        });

        return Redirect::action('UserController@showSettings')->with('success_msg', 'Na podany adres email został wysłany link umożliwiający potwierdzenie zmiany.');
    }

    public function confirmEmailChange($token)
    {
        if ($token != Auth::user()->email_change_token)
        {
            return Redirect::to('')->with('danger_msg', 'Błędny token.');
        }

        Auth::user()->changeEmailHashes(Auth::user()->new_email, Auth::user()->shadow_new_email);
        Auth::user()->unset(['email_change_token', 'new_email', 'shadow_new_email']);
        Auth::user()->save();

        return Redirect::to('')->with('success_msg', 'Adres email został zmieniony.');
    }

    public function remindPassword()
    {
        $reminders = App::make('auth.reminder.repository');

        if (Input::has('email'))
        {
            $email = Input::get('email');
            $user = User::where('email', hash_email(Str::lower($email)))->first();

            /*
            /Password::remind(array('email' => Str::lower(Input::get('email'))), function($message, $user)
            {
                $message->subject('Resetowanie hasła');
            });
            */

            if ($user)
            {
                $token = $reminders->create($user);
                $view = Config::get('auth.reminder.email');

                Mail::send($view, compact('token', 'user'), function($m) use ($user, $token, $email)
                {
                    $m->to($email)->subject('Resetowanie hasła');
                });
            }

            return Redirect::to('')->with('success_msg', 'Link umożliwiający zmianę hasła został wysłany na twój adres email.');
        }

        return View::make('user.remind');
    }

    public function showPasswordResetForm($token)
    {
        return View::make('user.reset')->with('token', $token);
    }

    public function resetPassword($token)
    {
        $credentials = Input::only('email', 'password', 'password_confirmation', 'token');

        $credentials['email'] = hash_email(Str::lower($credentials['email']));

        $response = Password::reset($credentials, function($user, $password)
        {
            // Email confirmed, we may activate account if user didn't that yet
            if ($user->activation_token)
            {
                if (Cache::has('registration.'. md5(Request::getClientIp())))
                {
                    return App::abort(500);
                }

                $user->unset('activation_token');
                $user->is_activated = true;
            }

            $user->password = $password;
            $user->save();
        });

        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('danger_msg', Lang::get($response));

            case Password::PASSWORD_RESET:
                return Redirect::to('/');
        }
    }

    public function showLoginForm()
    {
        return View::make('user.login');
    }

    public function showRegisterForm()
    {
        return View::make('user.register');
    }

    public function processRegistration()
    {
        $rules = [
            'username' => 'required|min:2|max:30|unique_ci:users,name|regex:/^[a-zA-Z0-9_]+$/i',
            'password' => 'required|min:6',
            'email' => 'required|email|unique_email:users|real_email'
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            Input::flash('only', ['username', 'email', 'remember']);
            return Redirect::action('UserController@showRegisterForm')->withErrors($validator);
        }

        if (Cache::has('registration.'. md5(Request::getClientIp())))
        {
            return App::abort(500);
        }

        $email = Str::lower(Input::get('email'));

        $user = new User();
        $user->_id = Input::get('username');
        $user->name = Input::get('username');
        $user->shadow_name = Str::lower(Input::get('username'));
        $user->password = Input::get('password');
        $user->email = $email;
        $user->activation_token = Str::random(16);
        $user->last_ip = Request::getClientIp();
        $user->settings = [];
        $user->save();

        Log::info('New user with email from domain: '. strstr($email, '@'));

        Mail::send('emails.auth.activate', compact('user'), function($message) use($user, $email)
        {
            $message->to($email, $user->name)->subject('Witaj na Strimoid.pl!');
        });

        return Redirect::to('')->with('success_msg',
            'Aby zakończyć rejestrację musisz jeszcze aktywować swoje konto, klikając na link przesłany na twój adres email.');
    }

    public function activateAccount($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        if (Cache::has('registration.'. md5(Request::getClientIp())))
        {
            return App::abort(500);
        }

        $user->unset('activation_token');
        $user->is_activated = true;
        $user->save();

        Auth::login($user);

        Cache::put('registration.'. md5(Request::getClientIp()), 'true', 60 * 24 * 7);

        return Redirect::to('/kreator')->with('success_msg',
            'Witaj w gronie użytkowników serwisu '. Config::get('app.site_name') .'! ;) '.
            'Zacznij od zasubskrybowania dowolnej ilości grup, pasujących do twoich zainteresowań.');
    }

    public function showRemoveAccountForm()
    {
        return View::make('user.remove');
    }

    public function removeAccount()
    {
        $validator = Validator::make(Input::all(), [
            'password' => 'required|confirmed|user_password',
        ]);

        if ($validator->fails())
        {
            return Redirect::action('UserController@showRemoveAccountForm')->withErrors($validator);
        }

        Auth::user()->removed_at = new MongoDate();
        Auth::user()->type = 'deleted';
        Auth::user()->unset(['age', 'description', 'email', 'location', 'password', 'sex', 'shadow_email']);
        Auth::user()->deleteAvatar();

        Auth::user()->save();

        Auth::logout();

        return Redirect::to('')->with('success_msg', 'Twoje konto zostało usunięte.');
    }

    public function showProfile($username, $type = 'all')
    {
        $user = User::where('shadow_name', Str::lower($username))->firstOrFail();

        if ($user->removed_at)
        {
            App::abort(404, 'Użytkownik usunął konto.');
        }

        if ($type == 'contents')
            $data['contents'] = Content::where('user_id', $user->getKey())->orderBy('created_at', 'desc')->paginate(15);
        elseif ($type == 'comments')
            $data['comments'] = Comment::where('user_id', $user->getKey())->orderBy('created_at', 'desc')->paginate(15);
        elseif ($type == 'comment_replies')
            $data['actions'] = UserAction::where('user_id', $user->getKey())
                ->where('type', UserAction::TYPE_COMMENT_REPLY)->orderBy('created_at', 'desc')->paginate(15);
        elseif ($type == 'entries')
            $data['entries'] = Entry::where('user_id', $user->getKey())->orderBy('created_at', 'desc')->paginate(15);
        elseif ($type == 'entry_replies')
            $data['actions'] = UserAction::where('user_id', $user->getKey())
                ->where('type', UserAction::TYPE_ENTRY_REPLY)->orderBy('created_at', 'desc')->paginate(15);
        elseif ($type == 'moderated')
            $data['moderated'] = GroupModerator::where('user_id', $user->getKey())->orderBy('created_at', 'desc')->paginate(15);
        else
            $data['actions'] = UserAction::where('user_id', $user->_id)->orderBy('created_at', 'desc')->paginate(15);

        if (isset($data['actions']))
        {
            foreach ($data['actions'] as $action)
            {
                if ($action->type == UserAction::TYPE_COMMENT_REPLY)
                    $action->reply = CommentReply::find($action->comment_reply_id);

                if ($action->type == UserAction::TYPE_ENTRY_REPLY)
                    $action->reply = EntryReply::find($action->entry_reply_id);
            }
        }

        $data['type'] = $type;
        $data['user'] = $user;

        return View::make('user.profile', $data);
    }

    public function showSettings()
    {
        $user = Auth::user();

        $subscribedGroups = GroupSubscriber::where('user_id', $user->getKey())->with('group')->get();
        $blockedGroups = GroupBlock::where('user_id', $user->getKey())->with('group')->get();
        $moderatedGroups = GroupModerator::where('user_id', $user->getKey())->with('group')->get();
        $blockedUsers = UserBlocked::where('user_id', $user->getKey())->with('user')->get();
        $bans = GroupBanned::where('user_id', $user->getKey())->with('group')->get();

        return View::make('user.settings', compact(
            'user', 'subscribedGroups', 'blockedGroups', 'moderatedGroups', 'blockedUsers', 'bans'
        ));
    }

    public function saveProfile()
    {
        $rules = array(
            'sex' => 'in:male,female',
            'avatar' => 'image|max:250',
            'age' => 'integer|min:1900|max:2010',
            'location' => 'max:32',
            'description' => 'max:250'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            Input::flash();
            return Redirect::action('UserController@showSettings')->withErrors($validator);
        }

        $user = Auth::user();

        $user->sex = Input::get('sex');
        $user->age = (int) Input::get('age');
        $user->location = Input::get('location');
        $user->description = Input::get('description');

        if (Input::hasFile('avatar'))
        {
            $user->setAvatar(Input::file('avatar')->getRealPath());
        }

        $user->save();

        return Redirect::action('UserController@showSettings')->with('success_msg', 'Zmiany zostały zapisane.');
    }

    public function saveSettings()
    {
        $user = Auth::user();

        $validator = Validator::make(Input::all(), [
            'css_style' => 'url|safe_url|max:250',
            'contents_per_page' => 'integer|min:1|max:100',
            'entries_per_page' => 'integer|min:1|max:100',
            'timezone' => 'timezone',
        ]);

        if ($validator->fails())
        {
            return Redirect::action('UserController@showSettings')->withInput()->withErrors($validator);
        }

        $settings['enter_send'] = Input::get('enter_send') == 'on' ? true : false;
        $settings['pin_navbar'] = Input::get('pin_navbar') == 'on' ? true : false;
        $settings['notifications_sound'] = Input::get('notifications_sound') == 'on' ? true : false;
        $settings['homepage_subscribed'] = Input::get('homepage_subscribed') == 'on' ? true : false;
        $settings['disable_groupstyles'] = Input::get('disable_groupstyles') == 'on' ? true : false;
        $settings['css_style'] = Input::get('css_style');
        $settings['contents_per_page'] = (int) Input::get('contents_per_page');
        $settings['entries_per_page'] = (int) Input::get('entries_per_page');
        $settings['timezone'] = Input::get('timezone');
        $settings['notifications']['auto_read'] = Input::get('notifications.auto_read') == 'on' ? true : false;

        $user->settings = $settings;
        $user->save();

        return Redirect::action('UserController@showSettings')->with('success_msg', 'Ustawienia zostały zapisane.');
    }

    public function blockUser()
    {
        $target = User::where('_id', Input::get('username'))->firstOrFail();

        if (UserBlocked::where('target_id', $target->_id)->where('user_id', Auth::user()->getKey())->first())
        {
            return Response::make('Already blocked', 400);
        }

        $block = new UserBlocked();
        $block->user()->associate(Auth::user());
        $block->target()->associate($target);
        $block->save();

        Cache::forget('user.'. Auth::user()->_id . '.blocked_users');

        return Response::json(['status' => 'ok']);
    }

    public function unblockUser()
    {
        $target = User::where('_id', Input::get('username'))->firstOrFail();

        $block = UserBlocked::where('target_id', $target->_id)->where('user_id', Auth::user()->getKey())->first();

        if (!$block)
        {
            return Response::make('Not blocked', 400);
        }

        $block->delete();

        Cache::forget('user.'. Auth::user()->_id . '.blocked_users');

        return Response::json(['status' => 'ok']);
    }

    public function observeUser()
    {
        $target = User::where('_id', Input::get('username'))->firstOrFail();

        if (Auth::user()->isObservingUser($target))
        {
            return Response::make('Already observed', 400);
        }

        Auth::user()->push('_observed_users', $target->_id);

        return Response::json(['status' => 'ok']);
    }

    public function unobserveUser()
    {
        $target = User::where('_id', Input::get('username'))->firstOrFail();

        if (!Auth::user()->isObservingUser($target))
        {
            return Response::make('Not observed', 400);
        }

        Auth::user()->pull('_observed_users', $target->_id);

        return Response::json(['status' => 'ok']);
    }

    public function blockDomain($domain)
    {
        $domain = PDP::parseUrl($domain)->host->registerableDomain;

        if (!$domain)
        {
            return Response::json(['status' => 'error', 'error' => 'Nieprawidłowa domena']);
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
        $user = User::where('shadow_name', Str::lower($username))->firstOrFail();

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
        $stats = array(
            'contents' => intval(Content::where('user_id', $user->getKey())->count()),
            'comments' => intval(Comment::where('user_id', $user->getKey())->count()),
            'entries' => intval(Entry::where('user_id', $user->getKey())->count()),
            'moderated_groups' => intval(GroupModerator::where('user_id', $user->getKey())->count()),
        );

        return array(
            '_id' => $user->_id,
            'name' => $user->name,
            'age' => $user->age,
            'avatar' => $user->avatar,
            'description' => $user->description,
            'joined' => current($user->created_at),
            'location' => $user->location,
            'sex' => $user->sex,
            'stats' => $stats,
        );
    }

}