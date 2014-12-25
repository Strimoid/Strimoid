<?php namespace Strimoid\Http\Controllers;

class OAuthController extends BaseController {

    public function getAccessToken()
    {
        $request = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
        $response = new OAuth2\HttpFoundationBridge\Response();

        return OAuth::handleTokenRequest($request, $response);
    }

    public function authorizationForm()
    {
        $request = Request::instance();

        if ( ! Input::has('state'))
        {
            $request->merge(['state' => 'state']);
        }

        $scope = Input::get('scope');

        if (Str::contains($scope, ','))
        {
            $scope = str_replace(',', ' ', $scope);
            $request->merge(['scope' => $scope]);
        }

        $request = OAuth2\HttpFoundationBridge\Request::createFromRequest($request);
        $response = new OAuth2\HttpFoundationBridge\Response();

        if ( ! OAuth::validateAuthorizeRequest($request, $response))
        {
            return OAuth::getResponse();
        }

        $client = OAuth\Client::findOrFail(Input::get('client_id'));

        $scopes = array();

        if (Input::has('scope'))
        {
            $scopes = explode(' ', Input::get('scope'));
        }
        else
        {
            $scopes[] = 'basic';
        }

        return View::make('oauth.authorize', ['client' => $client, 'scopes' => $scopes]);
    }

    public function authorize()
    {
        $authorized = (bool) Input::get('authorize');
        $request = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
        $response = new OAuth2\HttpFoundationBridge\Response();

        return OAuth::handleAuthorizeRequest($request, $response, $authorized, Auth::user()->_id);
    }

    public function listApps()
    {
        $apps = OAuth\Client::where('user_id', Auth::user()->_id)->get();

        return View::make('oauth.apps', ['apps' => $apps]);
    }

    public function addAppForm()
    {
        return View::make('oauth.add');
    }

    public function addApp()
    {
        $validator = Validator::make(Input::all(), [
            'name' => 'required|min:5|max:40',
            'redirect_url' => 'required|url|max:255'
        ]);

        if ($validator->fails())
        {
            Input::flash();
            return Redirect::action('OAuthController@addAppForm')->withErrors($validator);
        }

        $client = new OAuth\Client;

        $client->_id = Str::random(15);
        $client->name = Input::get('name');
        $client->client_id = $client->_id;
        $client->client_secret = Str::random(40);
        $client->redirect_uri = Input::get('redirect_url');
        $client->user()->associate(Auth::user());

        $client->save();

        return Redirect::action('OAuthController@listApps');
    }

}
