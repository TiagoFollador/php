<?php

use Alura\Reflection\ClasseExemplo;

require_once "vendor/autoload.php";

$reflectionClass = new ReflectionClass(ClasseExemplo::class);
$propriedade = $reflectionClass->getProperty('propriedadePublica');

var_dump($propriedade->getAttributes()[0]->newInstance());