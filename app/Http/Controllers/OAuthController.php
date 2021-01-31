<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\OAuth2\Server\AuthorizationServer;
use Illuminate\Support\Facades\Response;
use Strimoid\Models\OAuth\Client;

class OAuthController extends BaseController
{
    use ValidatesRequests;

    private AuthorizationServer $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    /**
     * Issue a new token.
     *
     */
    public function getAccessToken(): JsonResponse
    {
        try {
            $response = $this->server->issueAccessToken();

            return Response::json($response);
        } catch (OAuthException $e) {
            return Response::json([
                'error' => $e->errorType,
                'message' => $e->getMessage(),
            ], $e->httpStatusCode);
        }
    }

    public function listApps()
    {
        $apps = Client::where('user_id', Auth::id())->get();

        return view('oauth.apps', ['apps' => $apps]);
    }

    public function addAppForm()
    {
        return view('oauth.add');
    }

    public function addApp(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:5|max:40',
            'redirect_url' => 'required|url|max:255',
        ]);

        Client::create([
            'name' => $request->get('name'),
            'secret' => Str::random(40),
            'redirect_uri' => $request->get('redirect_url'),
            'user_id' => Auth::id(),
        ]);

        return Redirect::action('OAuthController@listApps');
    }
}
