<?php

require_once __DIR__ . '/vendor/autoload.php';

$nomeClasse = "Alura\Reflection\ClasseExemplo";
$nomeMetodo = "metodoPublico";

$objeto =  new $nomeClasse();


if (method_exists($objeto, $nomeMetodo)) {
    $objeto->$nomeMetodo();
}
