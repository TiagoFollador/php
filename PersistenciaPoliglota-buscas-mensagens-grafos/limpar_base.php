<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use MongoDB\Client as MongoClient;
use Elastic\Elasticsearch\ClientBuilder as ElasticClientBuilder;
use Laudis\Neo4j\ClientBuilder;

/** Zerar MongoDB */
function resetMongoDB(): void
{
    $client = new MongoClient("mongodb://usuario:senha@documentos:27017");
    $database = $client->selectDatabase('e_comerce');
    foreach ($database->listCollections() as $collection) {
        $database->selectCollection($collection->getName())->drop();
    }
    echo "MongoDB zerado!\n";
}

/** Zerar Elasticsearch */
function resetElasticsearch(): void
{
    $client = ElasticClientBuilder::create()->setHosts(['http://busca_textual:9200'])->build();

    // Obtém a lista de índices existentes
    $indices = $client->cat()->indices(['format' => 'json'])->asArray();

    foreach ($indices as $index) {
        $indexName = $index['index'];

        // Apenas remove índices que não são internos do Elasticsearch
        if (!str_starts_with($indexName, '.')) {
            try {
                $response = $client->indices()->delete(['index' => $indexName]);
                echo "✅ Índice removido: {$indexName}\n";
            } catch (Exception $e) {
                echo "⚠️ Erro ao excluir {$indexName}: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "🔄 Elasticsearch zerado!\n";
}

/** Zerar Banco de Grafos (Neo4j) */
function resetGraphDB(): void
{
    $client = ClientBuilder::create()
        ->withDriver('bolt', 'bolt://neo4j:12345678@grafos:7687')
        ->withDefaultDriver('bolt')
        ->build();

    $client->writeTransaction(static function ($transaction) {
        $transaction->run('MATCH (p:Produto) DETACH DELETE p');
    });
    echo "Neo4j (Produtos) zerado!\n";
}

// Executando as funções
// resetMongoDB();
// resetElasticsearch();
resetGraphDB();
