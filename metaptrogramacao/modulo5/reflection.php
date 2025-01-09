<?php

use Alura\Reflection\ClasseExemplo;

require_once __DIR__ . '/vendor/autoload.php';

$reflectionClass = new ReflectionClass(ClasseExemplo::class);

$objetoClasseExemplo = $reflectionClass->newInstanceWithOutConstructor();
$propriedadePrivada = $reflectionClass->getProperty('propriedadePrivada');
var_dump($propriedadePrivada->getValue($objetoClasseExemplo));

if (!$propriedadePrivada->isPublic()) {
    $propriedadePrivada->setAccessible(true);
}

var_dump($propriedadePrivada->getValue($objetoClasseExemplo));

// ------------------------------------------- Métodos ------------------------------------------

$reflectionMethod = $reflectionClass->getMethod('metodoPublico');
var_dump($reflectionMethod->getDocComment());

$reflectionMethod = $reflectionClass->getMethod('metodoProtegido');
$reflectionMethod->setAccessible(true);
var_dump($reflectionMethod->invoke($objetoClasseExemplo));

/*
 
 
 
 
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
 
 */