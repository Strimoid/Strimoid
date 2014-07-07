<?php

namespace Notifications;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServerInterface;
use Ratchet\Session\Storage\VirtualSessionStorage;
use Ratchet\Session\Serialize\HandlerInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NullSessionHandler;
use Illuminate\Encryption\Encrypter;
use Illuminate\Encryption\DecryptException;
use Illuminate\Session\Store;

class LaravelSessionProvider implements MessageComponentInterface, WsServerInterface {
    /**
     * @var \Ratchet\MessageComponentInterface
     */
    protected $_app;

    /**
     * Selected handler storage assigned by the developer
     * @var \SessionHandlerInterface
     */
    protected $_handler;

    /**
     * Null storage handler if no previous session was found
     * @var \SessionHandlerInterface
     */
    protected $_null;

    /**
    * @var \Illuminate\Encryption\Encrypter
    */
    protected $_encrypter;

    /**
     * @param \Ratchet\MessageComponentInterface          $app
     * @param \SessionHandlerInterface                    $handler
     * @param array                                       $options
     * @param \Ratchet\Session\Serialize\HandlerInterface $serializer
     * @throws \RuntimeException
     */
    public function __construct(MessageComponentInterface $app, \SessionHandlerInterface $handler,
                                array $options = array(), HandlerInterface $serializer = null) {
        $this->_app     = $app;
        $this->_handler = $handler;
        $this->_null    = new NullSessionHandler;
        $this->_encrypter = new Encrypter('obbU9uvmfqFM783l8iOcUq8wWzqiJ54d');

        ini_set('session.auto_start', 0);
        ini_set('session.cache_limiter', '');
        ini_set('session.use_cookies', 0);
    }

    /**
     * {@inheritdoc}
     */
    function onOpen(ConnectionInterface $conn) {
        $encryptedCookie = $conn->WebSocket->request->getCookie('laravel_session');
        $encryptedCookie = urldecode($encryptedCookie);

        try {
            $id = $this->_encrypter->decrypt($encryptedCookie);

            print 'session_id: '. $id ."\n";
        } catch (DecryptException $e) { }

        if (!isset($conn->WebSocket) || null === ($id = $conn->WebSocket->request->getCookie(ini_get('session.name')))) {
            $saveHandler = $this->_null;
            $id = '';
        } else {
            $saveHandler = $this->_handler;
        }

        // $conn->Session = new Session(new VirtualSessionStorage($saveHandler, $id, $this->_serializer));

        $conn->Store = new Store('maslo', $saveHandler, $id);
        $conn->Store->start();

        return $this->_app->onOpen($conn);
    }

    /**
     * {@inheritdoc}
     */
    function onMessage(ConnectionInterface $from, $msg) {
        return $this->_app->onMessage($from, $msg);
    }

    /**
     * {@inheritdoc}
     */
    function onClose(ConnectionInterface $conn) {
        // "close" session for Connection

        return $this->_app->onClose($conn);
    }

    /**
     * {@inheritdoc}
     */
    function onError(ConnectionInterface $conn, \Exception $e) {
        return $this->_app->onError($conn, $e);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubProtocols() {
        if ($this->_app instanceof WsServerInterface) {
            return $this->_app->getSubProtocols();
        } else {
            return array();
        }
    }
}
