<?php namespace Strimoid\Providers; 

use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\InvalidRequestException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\ResourceServer;
use Strimoid\OAuth\AccessTokenStorage;
use Strimoid\OAuth\AuthCodeStorage;
use Strimoid\OAuth\ClientStorage;
use Strimoid\OAuth\ScopeStorage;
use Strimoid\OAuth\SessionStorage;

class OAuthServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @param ResourceServer $server
     */
    public function boot(ResourceServer $server)
    {
        $this->app->booted(function() use($server)
        {
            try
            {
                if ($server->isValidRequest(false))
                {
                    $id = $server->getAccessToken()
                        ->getSession()
                        ->getOwnerId();
                    $this->app->auth->onceUsingId($id);
                }
            }
            catch(InvalidRequestException $e) {}
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('League\OAuth2\Server\AuthorizationServer', function()
        {
            $auth = $this->app->make('Illuminate\Contracts\Auth\Guard');
            $connection = $this->app['db']->connection();

            $server = new AuthorizationServer();
            $server->setSessionStorage(new SessionStorage($connection));
            $server->setAccessTokenStorage(new AccessTokenStorage($connection));
            $server->setClientStorage(new ClientStorage($connection));
            $server->setScopeStorage(new ScopeStorage($connection));
            $server->setAuthCodeStorage(new AuthCodeStorage($connection));

            $authCodeGrant = new AuthCodeGrant();
            $server->addGrantType($authCodeGrant);

            $passwordGrant = new PasswordGrant();
            $passwordGrant->setVerifyCredentialsCallback(
                function ($username, $password) use($auth) {
                    $isValid = $auth->validate([
                        'shadow_name'  => Str::lower($username),
                        'password'     => $password,
                        'is_activated' => true,
                    ]);

                    if ( ! $isValid) return false;

                    return $auth->getLastAttempted()
                        ->getAuthIdentifier();
                });
            $server->addGrantType($passwordGrant);

            return $server;
        });

        $this->app->singleton('League\OAuth2\Server\ResourceServer', function()
        {
            $connection = $this->app['db']->connection();

            $server = new ResourceServer(
                new SessionStorage($connection),
                new AccessTokenStorage($connection),
                new ClientStorage($connection),
                new ScopeStorage()
            );

            return $server;
        });
    }

}
