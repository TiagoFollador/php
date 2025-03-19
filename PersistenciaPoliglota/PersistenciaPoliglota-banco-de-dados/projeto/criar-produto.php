<?php

declare(strict_types=1);

use Elastic\Elasticsearch\ClientBuilder as ElasticSearchClientBuilder;
use Laudis\Neo4j\ClientBuilder as Neo4jClientBuilder;
use MongoDB\Client;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

// receber um produto no payload da requisição e inserir no MongoDB
$documento = json_decode(file_get_contents('php://input'), true);

if (empty($documento['nome'])) {
    http_response_code(422);

    echo json_encode([
        'status' => 'error',
        'msg' => 'Nome do produto é obrigatório'
    ]);
    return;
}

$mongodb = new Client('mongodb://usuario:senha@documentos');
$database = $mongodb->selectDatabase('projeto');

$colecaoDeProdutos = $database->selectCollection('produtos');
$resultado = $colecaoDeProdutos->insertOne($documento);

if ($resultado->getInsertedCount() === 0) {
    http_response_code(422);

    echo json_encode([
        'status' => 'error',
        'msg' => 'Erro ao inserir produto'
    ]);
    return;
}

// Inserir novo produto no ElasticSearch
$client = ElasticSearchClientBuilder::create()
    ->setHosts(['http://busca_textual:9200'])
    ->build();

$documento = [
    'id' => $resultado->getInsertedId(),
    'nome' => $documento['nome'],
];
$response = $client->index([
    'index' => 'projeto',
    'type' => 'produtos',
    'body' => $documento
]);

// Inserir produto no Neo4j
$client = Neo4jClientBuilder::create()
    ->withDriver('bolt', 'bolt://neo4j:12345678@grafos:7687')
    ->withDefaultDriver('bolt')
    ->build();

$result = $client->run('CREATE (p:Produto {id: $id, nome: $nome})', $documento);

echo json_encode([
    'status' => 'ok',
    'id' => $resultado->getInsertedId(),
]);
