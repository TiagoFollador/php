<?php

namespace Tiago\DesignPattern\Descontos;

use Tiago\DesignPattern\Orcamento;

abstract class Desconto
{
    public function __construct(protected ?Desconto $proximoDesconto) 
    {
        $this->proximoDesconto = $proximoDesconto;
    }
    abstract public function calculaDesconto(Orcamento $orcamento): float;
}