<?php

declare(strict_types=1);


$cluster = Cassandra::cluster()
    ->withContractPoints('colunares')
    ->build();


$session = $cluster->connect('e_comerce');

/*
$statement = $session->prepare('INSERT INTO products (poduct_id, name, price) VALUES (?,?,?);');
$rows = $session->execute($statement, [ //retorna um objeto row, com um array vazio
    'arguments' => [
        'product_id' => 1,
        'name' => 'Produto',
        'price' => 1000_00
    ]
]); */

$rows = $session->execute('SELECT * FROM products;');