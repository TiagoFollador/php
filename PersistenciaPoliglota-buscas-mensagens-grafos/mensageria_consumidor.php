<?php

declare(strict_types=1);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once './vendor/autoload.php';

$connection = new AMQPStreamConnection(
    'mensageria',
    5672,
    'guest',
    'guest'
);

$channel = $connection->channel();
/*
A gente manda mensagem paraa exchange e sempre tem ao menos uma fila ligada ao 
exchange(pode ser varias), ao criar uma exchange ele cria uma fila automaticamente

*/
$channel->queue_declare(
    'product_bought', // nome da fila
    auto_delete: false, // nao apagar mensagem ao chegar automaticamente
);

$msg = new AMQPMessage(
    '1234'
); // possui um corpo e propriedades []

$channel->basic_consume(
    'product_bought', // canal a ser consumido
    no_ack: true,
    callback: function (AMQPMessage $mensage) { // callback do que fazer, ao receber a mensagem
        echo "[x] Mensagem recebida: " . $mensage->body . PHP_EOL; 
        $mensage->ack();
});

try {
    $channel->consume(); // loop infinito para ouvir as mensagens
} catch (\Throwable $th) {
    var_dump($th);
}

$channel->close();
$connection->close();
