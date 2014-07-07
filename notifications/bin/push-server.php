<?php

    use Symfony\Component\HttpFoundation\Session\Storage\Handler;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $mongo = new MongoClient('mongodb://localhost');

    $loop   = React\EventLoop\Factory::create();
    $pusher = new Notifications\Pusher($mongo);

    // Listen for the web server to make a ZeroMQ push after an ajax request
    $context = new React\ZMQ\Context($loop);
    $pull = $context->getSocket(ZMQ::SOCKET_PULL);
    $pull->bind('tcp://127.0.0.1:5555');
    $pull->on('message', array($pusher, 'onMessage'));

    $session = new Notifications\LaravelSessionProvider(
        new Ratchet\Wamp\WampServer(
            $pusher
        ),
        new Handler\MongoDbSessionHandler($mongo, array(
            'database' => 'default',
            'collection' => 'sessions',
            'id_field'   => '_id',
            'data_field' => 'payload',
            'time_field' => 'last_activity'
        ))
    );

    // Set up our WebSocket server for clients wanting real-time updates
    $webSock = new React\Socket\Server($loop);
    $webSock->listen(8080, '127.0.0.1');
    $webServer = new Ratchet\Server\IoServer(
        new Ratchet\Http\HttpServer(
            new Ratchet\WebSocket\WsServer(
                $session
            )
        ),
        $webSock
    );

    $loop->run();
