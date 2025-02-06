<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;
use Laudis\Neo4j\Databags\Statement;
use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$mongodb = new Client('mongodb://usuario:senha@documentos');

$client = ClientBuilder::create()
    ->withDriver(  // pode ter varios driver
        'bolt', // nome 
        'bolt://neo4j:12345678@grafos:7687' // conexao
    )
    ->withDefaultDriver('bolt')
    ->build();

$connection = new AMQPStreamConnection(
    'mensageria',
    5672,
    'guest',
    'guest'
);

function verifyIfProductExists(string $productId): bool
{
    global $mongodb;
    $database = $mongodb->selectDatabase('e_comerce');
    $collection = $database->selectCollection('produtos');

    $product = $collection->findOne(['_id' => new ObjectId($productId)]);
    return !is_null($product);
}

function buyProduct(string $productId, string $userName): array
{
    global $client;
    if (verifyIfProductExists($productId)) {

        $result = $client->writeTransaction(static function (TransactionInterface $transaction) use ($productId, $userName) {
            return $transaction->runStatement(
                Statement::create(
                    'MATCH (user:Usuario {nome: $userName}), (produto:Produto {product_id: $productId}) CREATE (user)-[:COMPROU]->(produto) RETURN user, produto',
                    [
                        'userName' => $userName,
                        'productId' => $productId
                    ]
                )
            );
        });
        $message = $result->count() > 0 ? "Compra realizada com sucesso!" : "Compra nao realizada!";

        sendMsg($message);

        $returnMessage = [
            'message' =>  $message,
            'Usuario' => $userName,
            'Produto' => $productId
        ];

        return $returnMessage;
    } else {
        return [
            'message' => "Produto nao encontrado!"
        ];
    }
}


function sendMsg($msg)
{
    global $connection;
    $channel = $connection->channel();
    $channel->queue_declare(
        'product_bought',
        auto_delete: false,
    );
    $msg = new AMQPMessage(
        'novidade'
    );
    $channel->basic_publish(
        $msg,
        '',
        'product_bought'
    );


    $channel->close();
    $connection->close();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    echo json_encode(
        buyProduct($data['id_produto'], $data['nome_usuario'])
    );
}
