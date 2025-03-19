<?php

declare(strict_types=1);

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;

require_once './vendor/autoload.php';

$client = ClientBuilder::create()
    ->withDriver(  // pode ter varios driver
        'bolt', // nome 
        'bolt://neo4j:12345678@grafos:7687' // conexao
    )
    ->withDefaultDriver('bolt')
    ->build();

// var_dump($client->verifyConnectivity()); // retorna bool se esta conectado
/*
// CREATE (u:Usuario {nome: $nome }){os parenteses indicam oque eu vou criar, apos os "u:" representa a entidade que vou criar. Entre "{}" vao ter os atributos do que euvou criar}
 $result = $client->run(
     'CREATE (u:Usuario {nome: $nome})',
     ['nome' => 'Tiago'] // parametros utilizados no comando
 );
 var_dump($result->getSummary());

$client->writeTransaction(static function (TransactionInterface $transaction) { // oque retornar aqui dentro, retorna la fora
    $transaction->runStatements([
        Statement::create('CREATE (u:Usuario {nome: $nome})',['nome' => 'Wellington']),
        Statement::create('CREATE (u:Usuario {nome: $nome})',['nome' => 'Rafael']),
    ]);
});
*/

// $client->writeTransaction(static function (TransactionInterface $transaction) {
//     $transaction->runStatements([ // MATCH é como se fosse um select                                          
//         Statement::create(
//             'MATCH (tiago:Usuario {nome: "Tiago"}), (well:Usuario {nome: "Wellington"}) CREATE (tiago)-[:AMIZADE]->(well)' // (algo que vou chamar de tiago que é do tipo Usuario; nas chaves vou validar oque eu quero) --- estou buscando tiago e wellington
//         ),
//         //                                              CREATE (como chamei a primeira pesquisa)-[:nome do relacionamento]
//         Statement::create(
//             'MATCH (well:Usuario {nome: "Wellington"}), (rafael:Usuario {nome: "Rafa"}) CREATE (well)-[:AMIZADE]->(rafael)' // (algo que vou chamar de tiago que é do tipo Usuario; nas chaves vou validar oque eu quero) --- estou buscando tiago e wellington
//         ),
//     ]);
// });
 
$result = $client->readTransaction(static function (TransactionInterface $transaction) {
    $comando = // como chamar os matchs ;; busca todos os nos com uma distancia =2, exemplo tiago -> well -> rafael (vai retornar o rafael); caso queira 2 a 3, adicionar ..{num}
        'MATCH (tiago:Usuario {nome: "Tiago"})-[:AMIZADE*2..3]-(sugestao:Usuario) ' .
        'WHERE NOT (tiago)-[:AMIZADE]-(sugestao) ' . // busca relacionamentos que nao tenham sido feitos por tiago
        'RETURN sugestao.nome'; // retorna o nome da sugestao
    return $transaction->run($comando);
});
/** @var CypherMap $result */
foreach ($result as $matchs) {
    echo $matchs->get('sugestao.nome') . PHP_EOL;
}
