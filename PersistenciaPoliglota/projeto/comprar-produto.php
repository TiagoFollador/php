<?php

declare(strict_types=1);

use Laudis\Neo4j\ClientBuilder;
use MongoDB\BSON\ObjectId;
use MongoDB\Client;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$corpoRequisicao = json_decode(file_get_contents('php://input'), true);
if (empty($corpoRequisicao['id_produto']) || empty($corpoRequisicao['nome_usuario'])) {
    http_response_code(422);

    echo json_encode([
        'status' => 'error',
        'msg' => 'id_produto e nome_usuario obrigatórios'
    ]);
    return;
}

$mongodb = new Client('mongodb://usuario:senha@documentos');
$database = $mongodb->selectDatabase('projeto');

$colecaoDeProdutos = $database->selectCollection('produtos');
/** @var \MongoDB\Model\BSONDocument $produto */
$produto = $colecaoDeProdutos->findOne(['_id' => new ObjectId($corpoRequisicao['id_produto'])]);

if ($produto === null) {
    http_response_code(404);

    echo json_encode([
        'status' => 'error',
        'msg' => 'Produto não encontrado'
    ]);
    return;
}

// Adiciona relacionamento de compra
$client = ClientBuilder::create()
    ->withDriver('bolt', 'bolt://neo4j:12345678@grafos:7687')
    ->withDefaultDriver('bolt')
    ->build();

$client->run(
    'MATCH (p:Produto {id: $produto}), (u:Usuario {nome: $usuario}) CREATE (u)-[:COMPROU]->(p)',
    [
        'produto' => $corpoRequisicao['id_produto'],
        'usuario' => $corpoRequisicao['nome_usuario'],
    ]
);

$connection = new AMQPStreamConnection(
    'mensageria',
    5672,
    'guest',
    'guest'
);
$channel = $connection->channel();
$msg = new AMQPMessage(json_encode(['id' => $corpoRequisicao['id_produto']]));
$queue = 'product_bought';
$channel->queue_declare($queue, auto_delete: false);
$channel->basic_publish($msg, routing_key: $queue);

$channel->close();
$connection->close();

echo json_encode([
    'status' => 'ok',
    'msg' => 'Compra registrada com sucesso',
    'produto_comprado' => $produto,
]);
