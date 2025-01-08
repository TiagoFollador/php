<?php

use Alura\Reflection\ClasseExemplo;

require_once __DIR__ . '/vendor/autoload.php';

$reflectionClass = new ReflectionClass(ClasseExemplo::class);

$modifiers = $reflectionClass->getModifiers();
$modifierNames = Reflection::getModifierNames($modifiers);

$objetoClasseExemplo = $reflectionClass->newInstance();

echo json_encode($objetoClasseExemplo);
