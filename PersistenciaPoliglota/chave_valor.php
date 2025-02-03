<?php

declare(strict_types=1);

$redis = new Redis();
$redis->connect('chave_valor');

$redis->hMSet('carrinho:2', ['item' => 3, 'qtd' => 2]);

echo $redis->hGet('carrinho:2', 'qtd');