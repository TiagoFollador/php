<?php

declare(strict_types=1);

use Elastic\Elasticsearch\ClientBuilder;

require_once 'vendor/autoload.php';

$client = ClientBuilder::create()
    ->setHosts(['http://busca_textual:9200'])
    ->build();
/*
$response = $client->indices()->create([
    'index' => 'meu_indice'
]);

var_dump($response);


$documento = [
    'nome' => 'Tiago Follador',
    'usuario' => 'Tiago_Follador97'
];

$reponse = $client->index([
    "index" => 'meu_indice',
    'type' => 'usuarios',
    'body' => $documento
]);
*/

$reponse = $client->search([
    'index' => 'meu_indice',
    'type' => 'usuarios',
    'body' => [
        'query' => [
            'match' => [
                'nome' => 'Tiago'
            ]
        ]
    ]
]);
var_dump($reponse['hits']['hits']);

echo $reponse . PHP_EOL;
