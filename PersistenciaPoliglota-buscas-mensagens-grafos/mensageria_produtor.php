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
    'novidade'
); // possui um corpo e propriedades []
$channel->basic_publish(
    $msg, // pode mandar para uma fila ou para uma exchange
    '', // exchange (poderia estar configurada para mandar a varias filas)
    'product_bought' // routting key (fila)
);

echo "Mensagem enviada" . PHP_EOL;

$channel->close();
$connection->close();
