<?php

require_once __DIR__ . '/vendor/autoload.php';

echo __LINE__ . PHP_EOL;

$idade = 22;

$variavel = 'idade';

echo $$variavel;

$nomeCompletoClasse = 'Alura\Reflection\ClasseExemplo';
$nomeMetodo = 'metodoPublico';

$objeto = new $nomeCompletoClasse();

if (method_exists($nomeCompletoClasse, $nomeMetodo)) {
    echo PHP_EOL;
    $objeto->$nomeMetodo();
    echo PHP_EOL;
}

var_dump($objeto);

var_dump(get_object_vars($objeto));

var_dump(is_a($objeto, \Alura\Reflection\ClasseExemplo::class));
var_dump($objeto instanceof \Alura\Reflection\ClasseExemplo);
