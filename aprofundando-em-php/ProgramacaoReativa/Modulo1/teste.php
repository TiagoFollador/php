<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

 require_once "vendor/autoload.php";


 $client = new Client();

 $promisse1 =  $client->getAsync("http://localhost:8080/http-server.php");
 $promisse2 =  $client->getAsync("http://localhost:8000/http-server.php");

/** @var ResponseInterface[] $respostas */
$respostas = Utils::unwrap([
    $promisse1, $promisse2
]);

 echo "resposta 1: " . $respostas[0]->getBody()->getContents() . PHP_EOL;

 echo "resposta 2: " . $respostas[1]->getBody()->getContents() . PHP_EOL;