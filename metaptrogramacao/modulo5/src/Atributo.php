<?php
namespace Alura\Reflection;

use Attribute;

#[Attribute]
class Atributo 
{
    public function __construct(public int $valor){}
}