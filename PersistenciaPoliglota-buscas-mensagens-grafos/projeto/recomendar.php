<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;
use Laudis\Neo4j\Databags\Statement;
/*
'MATCH (usuario:Usuario {nome: $usuario})-[:COMPROU]->(produtoComprado:Produto)
WITH collect(produtoComprado.id) AS produtosComprados, usuario

MATCH (outros:Usuario)-[:COMPROU]->(produtoSugerido:Produto)
WHERE outros <> usuario AND NOT produtoSugerido.id IN produtosComprados

RETURN DISTINCT produtoSugerido'
*/

$client = ClientBuilder::create()
    ->withDriver(  // pode ter varios driver
        'bolt', // nome 
        'bolt://neo4j:12345678@grafos:7687' // conexao
    )
    ->withDefaultDriver('bolt')
    ->build();

function recommend(string $userName = ""): array
{
    global $client;

    $result = $client->writeTransaction(static function (TransactionInterface $transaction) use ($userName) {
        return $transaction->runStatement(
            Statement::create(
                ('MATCH (usuario:Usuario {nome: $userName})-[:COMPROU]->(produtoComprado:Produto) ' .
                'WITH collect(produtoComprado.product_id) AS produtosComprados, usuario ' .
                            
                'MATCH (outros:Usuario)-[:COMPROU]->(produtoSugerido:Produto) '. 
                'WHERE outros <> usuario AND NOT produtoSugerido.product_id IN produtosComprados '.
                            
                'RETURN DISTINCT produtoSugerido'),
                [
                    'userName' => $userName,
                ]
            )
        );
    });

    $productData = [];
    $count = 0;
    foreach ($result as $row) {
        $count += 1;
        if (isset($row['produtoSugerido'])) {
            $produtoSugerido = $row['produtoSugerido'];
            if (isset($produtoSugerido['properties'])) {
                $productData[] = $produtoSugerido['properties'];
            }
        }
    }

    return [
        "found_products" => $productData
    ];
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $param = $_GET['usuario'] ?? null;
    echo json_encode(
        recommend($param)
    );
}
