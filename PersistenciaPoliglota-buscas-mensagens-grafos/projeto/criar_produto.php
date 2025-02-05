<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use Elastic\Elasticsearch\ClientBuilder as ElasticClientBuilder;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;

$mongodb = new Client('mongodb://usuario:senha@documentos');

$client = ElasticClientBuilder::create()
    ->setHosts(['http://busca_textual:9200'])
    ->build();
$client->indices()->create([
    'index' => 'my_index'
]);

$clientGraph = ClientBuilder::create()
    ->withDriver(  // pode ter varios driver
        'bolt', // nome 
        'bolt://neo4j:12345678@grafos:7687' // conexao
    )
    ->withDefaultDriver('bolt')
    ->build();


function createProduct(string $name = null, int $price = null, string $description = null)
{
    if (!is_null($name) && !is_null($price) && !is_null($description)) {
        $resultMongoDB = saveInMongoDB($name, $price, $description);
        $resultElasticsearch = saveInElasticsearch($name);
        $resultGraphs = saveInGraphs($name);
    }
}

function saveInMongoDB(string $name = null, int $price = null, string $description = null)
{
    global $mongodb;
    $database = $mongodb->selectDatabase('e_comerce');

    $collection = $database->selectCollection('produtos');
    $result = $collection->insertOne([
        'name' => $name,
        'price' => $price,
        'description' => $description
    ]);
    return $result;
}

function saveInElasticsearch(string $name = null)
{
    global $client;
    if (!is_null($name)) {
        $document = [
            'name' => $name
        ];

        $response = $client->index([
            "index" => 'my_index',
            'type' => 'usuarios',
            'body' => $document
        ]);

        return $response;
    }
}

function saveInGraphs(string $name)
{
    global $clientGraph;

    $result = $clientGraph->writeTransaction(static function (TransactionInterface $transaction) use ($name) {
        $transaction->runStatements(
            [
                'CREATE (p:Produto {product_name: $product_name})',
                ['product_name' => $name]
            ]
        );
    });
    return $result;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    echo json_encode([
        "conexao?" => $data
    ]);
}



echo json_encode([
    "conexao?" => 'alo alo!'
]);
