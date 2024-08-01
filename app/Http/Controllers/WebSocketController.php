<?php

namespace App\Http\Controllers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{
    private $connections = [];

    public function __construct()
    {
        echo "[" . date('D M j G:i:s Y') . "] PHP Websocket Server started\n";
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $name = 'Guest (' . $conn->resourceId . ')';
        $this->connections[$conn->resourceId] = compact('conn') + ['user_id' => $conn->resourceId, 'user_name' => $name];

        foreach ($this->connections as $resourceId => &$connection) {
            $connection['conn']->send(json_encode(['type' => 'connect', 'data' => ['user_id' => $conn->resourceId, 'onlineUsers' => $this->connections]]));
        }

        // $this->connections[$conn->resourceId]['conn']->send(json_encode(['type' => 'connect', 'data' => ['user_id' => $conn->resourceId]]));

        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $disconnectedId = $conn->resourceId;
        unset($this->connections[$disconnectedId]);

        foreach ($this->connections as $resourceId => &$connection) {
            if ($conn->resourceId != $resourceId) {
                $connection['conn']->send(json_encode(['type' => 'disconnect', 'data' => ['onlineUsers' => $this->connections]]));
            }
        }

        echo "Connection closed! ({$conn->resourceId})\n";
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // $userId = $this->connections[$conn->resourceId]['user_id'];
        // echo "An error has occurred with user $userId: {$e->getMessage()}\n";
        // unset($this->connections[$conn->resourceId]);
        // $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $conn, $json)
    {
        // if (is_null($this->connections[$conn->resourceId]['user_id'])) {

        // $msg = htmlspecialchars(htmlspecialchars_decode($msg));
        $data = json_decode($json);

        if (isset($data->user_id)) {
            $user_name = $data->user_name;
            $user_image = $data->user_image;
            $this->connections[$conn->resourceId]['user_name'] = $user_name;
            $this->connections[$conn->resourceId]['user_image'] = $user_image;

            foreach ($this->connections as $resourceId => &$connection) {
                $connection['conn']->send(json_encode(['type' => 'connect', 'data' => ['onlineUsers' => $this->connections]]));
            }
        }

        if (isset($data->toUser)) {
            $toUser = $data->toUser;
            $msg = htmlspecialchars(htmlspecialchars_decode($data->mMessage));

            // $this->connections[$conn->resourceId]['user_id'] = $msg;
            // $onlineUsers = [];
            foreach ($this->connections as $resourceId => &$connection) {
                // $connection['conn']->send(json_encode([$conn->resourceId => $msg]));
                if ($toUser == $resourceId) {
                    $connection['conn']->send(json_encode(['type' => 'message', 'data' => ['id' => $conn->resourceId, 'msg' => $msg]]));
                    // $onlineUsers[$resourceId] = $connection['user_id'];
                }
            }
        }
        // $conn->send(json_encode(['online_users' => $onlineUsers]));
        // } 
        // else {
        //     $fromUserId = $this->connections[$conn->resourceId]['user_id'];
        //     $msg = json_decode($msg, true);
        //     $this->connections[$msg['to']]['conn']->send(json_encode([
        //         'msg' => $msg['content'],
        //         'from_user_id' => $fromUserId,
        //         'from_resource_id' => $conn->resourceId
        //     ]));
        // }
    }
}
