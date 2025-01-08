<?php

use Alura\Reflection\ClasseExemplo;

require_once __DIR__ . '/vendor/autoload.php';

$reflectionClass = new ReflectionClass(ClasseExemplo::class);

$objetoClasseExemplo = $reflectionClass->newInstanceWithOutConstructor();

$reflectionMethod = $reflectionClass->getMethod('metodoPublico');
var_dump($reflectionMethod->getParameters());

$reflectionMethod->invokeArgs($objetoClasseExemplo, ["Ante diegmon", 42]);



