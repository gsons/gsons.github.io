<?php
require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SignalingServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // $index = file_get_contents('public');
        // $conn->send($index);

        // 将新连接添加到客户端列表中
        $this->clients->attach($conn);
        echo "New client connected: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        switch ($data['type']) {
            case 'sdpOffer':
                // 将 SDP offer 转发给其他客户端
                foreach ($this->clients as $client) {
                    if ($client !== $from) {
                        $client->send(json_encode(array(
                            'type' => 'sdpOffer',
                            'offer' => $data['offer']
                        )));
                    }
                }
                break;

            case 'sdpAnswer':
                // 将 SDP answer 转发给其他客户端
                foreach ($this->clients as $client) {
                    if ($client !== $from) {
                        $client->send(json_encode(array(
                            'type' => 'sdpAnswer',
                            'answer' => $data['answer']
                        )));
                    }
                }
                break;

            case 'iceCandidate':
                // 将 ICE 候选地址信息转发给其他客户端
                foreach ($this->clients as $client) {
                    if ($client !== $from) {
                        $client->send(json_encode(array(
                            'type' => 'iceCandidate',
                            'candidate' => $data['candidate']
                        )));
                    }
                }
                break;

            case 'message':
                // 将聊天室消息转发给其他客户端
                foreach ($this->clients as $client) {
                    if (
                        $client !== $from
                    ) {
                        $client->send(json_encode(array(
                            'type' => 'message',
                            'message' => $data['message']
                        )));
                    }
                }
                break;
            default:
                break;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // 从客户端列表中删除断开连接的客户端
        $this->clients->detach($conn);
        echo "Client disconnected: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}



$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new SignalingServer()
        )
    ),
    9090
);

// $server->getRoot()->attach(( __DIR__ . '/public'));

$server->run();
