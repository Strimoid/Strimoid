<?php

/*
|--------------------------------------------------------------------------
| OAuth
|--------------------------------------------------------------------------
*/

App::bind('oauth', function()
{
    $storage = new OAuth2\Storage\Mongo(DB::getMongoDB());
    $server = new OAuth2\Server($storage, array(
        'allow_implicit' => true,
        'access_lifetime' => 0,
    ));

    $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

    $supportedScopes = array('basic', 'contents', 'entries', 'notifications', 'conversations', 'groups');
    $memory = new OAuth2\Storage\Memory(array(
        'default_scope' => 'basic',
        'supported_scopes' => $supportedScopes
    ));
    $scopeUtil = new OAuth2\Scope($memory);

    $server->setScopeUtil($scopeUtil);

    return $server;
});

/*
|--------------------------------------------------------------------------
| Guzzle
|--------------------------------------------------------------------------
*/

App::bind('guzzle', function()
{
    $client = new GuzzleHttp\Client([
        'defaults' => [
            'timeout'         => 10,
            'connect_timeout' => 5
        ]
    ]);

    return $client;
});

/*
|--------------------------------------------------------------------------
| User Settings
|--------------------------------------------------------------------------
*/

App::bind('settings', function()
{
    return new UserSettings();
});

/*
|--------------------------------------------------------------------------
| PHP Domain Parser
|--------------------------------------------------------------------------
*/

App::bind('pdp', function()
{
    $pslManager = new Pdp\PublicSuffixListManager();
    $parser = new Pdp\Parser($pslManager->getList());

    return $parser;
});

/*
|--------------------------------------------------------------------------
| ZMQ - used to send WebSocket messages
|--------------------------------------------------------------------------
*/

App::bind('ws', function()
{
    $context = new ZMQContext();

    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'laravel');
    $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 100);
    $socket->connect(Config::get('app.ws_address'));

    return $socket;
});
