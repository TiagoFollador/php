<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()
    ->setHosts(['http://busca_textual:9200'])
    ->build();

function search(string $productName) 
{
    global $client;
    $response = $client->search([
        'index' => 'my_index',
        'body' => [
            'query' => [
                'match' => [
                    'product_name' => $productName
                ]
            ]
        ]
    ]);

    $foundProducts = [];
    foreach ($response['hits']['hits'] as $value) {
        array_push($foundProducts, $value['_source']);
    }

    return $foundProducts;
}


$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $param = $_GET['busca'] ?? null;
    echo json_encode([
        "found_products" => search($param)
    ]);
}