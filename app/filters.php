<?php

use Guzzle\Http\Client;

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	if (Input::has('ntf_read') && Auth::check())
    {
        $id = b58_to_mid(Input::get('ntf_read'));

        Notification::where('_id', $id)
            ->target(['user_id' => Auth::id()])
            ->update(['_targets.$.read' => true]);

        WS::send(json_encode([
            'topic' => 'u.'. Auth::id(),
            'tag' => Input::get('ntf_read'),
            'type' => 'notification_read'
        ]));
    }
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
    {
        return Redirect::guest('login');
    }

    if (Auth::user()->removed_at || Auth::user()->blocked_at)
    {
        Auth::logout();
        return Redirect::guest('login');
    }

    if (Auth::user()->last_ip != Request::getClientIp())
    {
        Auth::user()->last_ip = Request::getClientIp();
        Auth::user()->save();
    }
});

Route::filter('auth.ajax', function()
{
    if (Auth::guest())
    {
        return Response::make('Unauthorized', 403);
    }

    //App::abort(403, 'Unauthorized');
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('oauth', function($route, $request, $value = '')
{
    if (Auth::check())
    {
        return;
    }

    if ($request->getUser() && $request->getPassword())
    {
        return Auth::onceBasic('_id');
    }

    $request = OAuth2\HttpFoundationBridge\Request::createFromRequest($request);
    $verified = OAuth::verifyResourceRequest($request, new OAuth2\HttpFoundationBridge\Response(), $value);

    if (!$verified && $value) {
        OAuth::getResponse()->send();
        die;
    }

    if ($verified) {
        $token = OAuth::getAccessTokenData($request);
        Auth::setUser(User::find($token['user_id']));
    }
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check())
    {
        return Redirect::to('/');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');

	if (Session::token() != $token)
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

Route::filter('anti_flood', function()
{
    $floodDetected = false;
    $key = 'lara.anti_flood.';

    // Max 1 action per 3 seconds
    if (!apc_add($key .'1.'. Auth::user()->_id, 1, 3))
    {
        $floodDetected = true;
    }

    // Max 10 actions per 1 minute
    apc_add($key .'2.'. Auth::user()->_id, 0, 60);
    $count = apc_inc($key .'2.'. Auth::user()->_id);

    if ($count > 10)
    {
        $floodDetected = true;
    }

    // Max 50 actions per 1 hour
    apc_add($key .'3.'. Auth::user()->_id, 0, 300);
    $count = apc_inc($key  .'3.'. Auth::user()->_id);

    if ($count > 50)
    {
        $floodDetected = true;
    }

    if ($floodDetected)
    {
        if (Request::ajax())
        {
            return Response::json(['status' => 'error', 'error' => 'Nie flooduj']);
        }
        else
        {
            return Redirect::back()->with('danger_msg', 'Nie flooduj')->withInput();
        }
    }
});

Route::filter('vote_anti_flood', function()
{
    $key = 'lara.vote_anti_flood.';

    if (!apc_add($key .'1.'. Auth::user()->_id, 1, 1))
        App::abort(403, 'Nie flooduj.');

    apc_add($key .'2.'. Auth::user()->_id, 0, 60);
    $count = apc_inc($key .'2.'. Auth::user()->_id);

    if ($count > 25)
        App::abort(403, 'Nie flooduj.');

    apc_add($key .'3.'. Auth::user()->_id, 0, 300);
    $count = apc_inc($key  .'3.'. Auth::user()->_id);

    if ($count > 50)
        App::abort(403, 'Nie flooduj.');
});

Route::filter('anti_spam', function()
{
    $isSpammer = false;
    $ip = Request::getClientIp();

    $bannedIps = ['213.189.45.189', '89.69.201.49'];

    if (in_array($ip, $bannedIps))
    {
        $isSpammer = true;
    }

    try
    {
        $response = Guzzle::get('http://www.stopforumspam.com/api?ip='. $ip .'&f=json')->send();
        $result = json_decode($response->getBody());

        if ($result->ip->appears == 1 && $result->ip->confidence > 50)
        {
            $isSpammer = true;
        }
    }
    catch(Exception $e) {}

    try
    {
        $country = geoip_country_code_by_name($ip);
        $blockedCountries = array('A1', 'BR', 'CN', 'ID', 'NG', 'TR', 'RU', 'VN') ;

        if (in_array($country, $blockedCountries))
        {
            $isSpammer = true;
        }
    }
    catch(Exception $e) {}

    if ($isSpammer)
    {
        return App::abort(500);
    }
});

