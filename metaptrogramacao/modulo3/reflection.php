<?php

use Alura\Reflection\ClasseExemplo;

require_once __DIR__ . '/vendor/autoload.php';

$reflectionClass = new ReflectionClass(ClasseExemplo::class);

$objetoClasseExemplo = $reflectionClass->newInstanceWithOutConstructor();

$reflectionMethod = $reflectionClass->getMethod('metodoPublico');
$parameters = array_filter(
    $reflectionMethod->getParameters(), 
    fn (ReflectionParameter $parameter) => !$parameter->isOptional()
);
foreach ($parameters as $parameter) {
    if (!$parameter->hasType()) {
        throw new DomainException('Nao tem tipo :(');
    }
    
    $tipo = (string) $parameter->getType();
     var_dump($tipo, $parameter->getType()->isBuiltin());
}


$reflectionMethod->invokeArgs($objetoClasseExemplo, ["Ante diegmon", 42]);



