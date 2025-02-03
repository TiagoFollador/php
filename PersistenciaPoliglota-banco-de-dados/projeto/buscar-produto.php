<?php

declare(strict_types=1);

use Elastic\Elasticsearch\ClientBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$busca = filter_input(INPUT_GET, 'busca');

if (empty($busca)) {
    http_response_code(422);

    echo json_encode([
        'status' => 'error',
        'msg' => 'Parâmetro query "busca" é obrigatório'
    ]);
    return;
}

$client = ClientBuilder::create()
    ->setHosts(['http://busca_textual:9200'])
    ->build();

$response = $client->search([
    'index' => 'projeto',
    'type' => 'produtos',
    'body' => [
        'query' => [
            'match' => [
                'nome' => $busca
            ]
        ]
    ]
]);

echo json_encode($response['hits']['hits']);
