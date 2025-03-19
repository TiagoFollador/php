<?php

declare(strict_types=1);

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$usuario = filter_input(INPUT_GET, 'usuario');

if (empty($usuario)) {
    http_response_code(422);

    echo json_encode([
        'status' => 'error',
        'msg' => 'Parâmetro query "usuario" é obrigatório'
    ]);
    return;
}

$client = ClientBuilder::create()
    ->withDriver('bolt', 'bolt://neo4j:12345678@grafos:7687')
    ->withDefaultDriver('bolt')
    ->build();

$result = $client->run(
    <<<'CYPHER'
    MATCH (usuario:Usuario {nome: $usuario})-[:COMPROU]->(produtoComprado:Produto)
    WITH collect(produtoComprado.id) AS produtosComprados, usuario

    MATCH (outros:Usuario)-[:COMPROU]->(produtoSugerido:Produto)
    WHERE outros <> usuario AND NOT produtoSugerido.id IN produtosComprados
    
    RETURN DISTINCT produtoSugerido
    CYPHER,
    [
        'usuario' => $usuario,
    ]
);

if (count($result) === 0) {
    http_response_code(404);
    echo json_encode([
        'status' => 'ok',
        'msg' => 'Nenhum produto sugerido',
    ]);

    return;
}

echo json_encode([
    'status' => 'ok',
    'produtos' => array_map(static function (array $item): CypherMap {
        /** @var Node $node */
        $node = $item['produtoSugerido'];
        return $node->getProperties();
    }, $result->toRecursiveArray()),
]);
