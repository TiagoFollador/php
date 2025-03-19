<?php

use Psr\Http\Message\MessageInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\MessageInterface as RatchetMessageInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require_once "vendor/autoload.php";

$chatComponent = new class implements MessageComponentInterface {
    private SplObjectStorage $connections;
    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }
    public function onOpen(ConnectionInterface $con)
    {
        echo "Nova conexão regitrada" . PHP_EOL;
        $this->connections->attach($con);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->connections as $connection) {
            if ($connection !== $from) {

                $connection->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $con)
    {
        echo "cONEXÃO ENCERRRADA    " . PHP_EOL;
        $this->connections->detach($con);
    }

    public function onError(ConnectionInterface $con, Exception $e)
    {
        echo "Erro: " . $e->getTraceAsString();
    }
};

$server = IoServer::factory(new HttpServer(new WsServer($chatComponent)), 8002);

$server->run();
