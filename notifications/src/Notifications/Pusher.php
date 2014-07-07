<?php

namespace Notifications;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Wamp\WampServerInterface;
use Illuminate\Encryption\Encrypter;
use Illuminate\Encryption\DecryptException;

class Pusher implements WampServerInterface {

    private $mongo, $encrypter;

    public function __construct($mongo) {
        $this->mongo = $mongo;
        $this->encrypter = new Encrypter('obbU9uvmfqFM783l8iOcUq8wWzqiJ54d');
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }


    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $username = '';

        if ($conn->Store) {
            $username = $conn->Store->get('login_82e5d2c56bdd0811318f0cf078b78bfc');
        }

        // print("u: ". $username ."\n");

        try {
            if (!$username) {
                $rememberEncrypted = $conn->WebSocket->request->getCookie('remember_82e5d2c56bdd0811318f0cf078b78bfc');
                $rememberEncrypted = urldecode($rememberEncrypted);

                $remember = $this->encrypter->decrypt($rememberEncrypted);
                $remember = explode('|', $remember, 2);

                list($id, $token) = $remember;

                $user = $this->mongo->default->users->findOne(['_id' => $id, 'remember_token' => $token], ['_id' => true]);

                if ($user) {
                    $username = $user['_id'];
                }

                // print("r: ". $username ."\n");
            }
        } catch (DecryptException $e) {
            print("cannot decrypt ". $conn->WebSocket->request->getCookie('remember_82e5d2c56bdd0811318f0cf078b78bfc') ."\n");
        }

        if (strpos($topic->getId(), 'u.') === 0 && $username !== substr($topic->getId(), 2)) {
            $conn->close();
        }

        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onMessage($message) {
        $msgData = json_decode($message, true);

        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($msgData['topic'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$msgData['topic']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($msgData);
    }

}

