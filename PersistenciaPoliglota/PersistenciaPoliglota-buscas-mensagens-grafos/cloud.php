<?php

declare(strict_types=1);

use Aws\DynamoDb\DynamoDbClient;

require_once __DIR__ . '/vendor/autoload.php';

$dynamo = new DynamoDbClient([
    'region' => 'us_east_1',
    'credentials' => [
        'key' => 'AKIAIOSFODNN7EXAMPLE',
        'secret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY'
    ]
]);

/* $resultado = $dynamo->putItem([
    'TableName' => 'Product_Catalog',
    'Item' => [
        'id' => ['S' => '1'],
        'name' => ['S' => 'TV Samsung'],
        'descricao' => ['S' => 'Uma TV de 50 polegadas'],
        'polegadas' => ['N' => '50']
    ]
]);
*/

$result = $dynamo->query([
    'ExpressionAttributeNames' => [
        ':id' => [
            'S' => '1'
        ],
    ],
    'KeyConditionExpression' => 'id = :id',
    'TableName' => 'Product_Catalog',
]);

var_dump($result->get('Items'));