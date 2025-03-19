<?php

declare(strict_types=1);

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

require_once __DIR__ . '/vendor/autoload.php';

$mongodb = new Client('mongodb://usuario:senha@documentos');
$database = $mongodb->selectDatabase('e_comerce');

$colecaoDeProdutos = $database->selectCollection('produtos');
/*
$resultado = $colecaoDeProdutos->insertOne ([
    'name' => 'TV Samsung',
    'descricao' => 'Uma Tv de 50 polegadas',
    'polegadas' => 50
]);

echo "Foram inseridos {$resultado->getInsertedCount()} iten(s)." . PHP_EOL . " Id da ultima inserção: {$resultado->getInsertedId()}" . PHP_EOL;
*/

$produto = $colecaoDeProdutos->findOne([
    '_id' => new  ObjectId('67a0b457b6568312440d5c62')
]);

echo "Essa TV tem {$produto->polegadas} polegadas" . PHP_EOL;