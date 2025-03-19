<?php

namespace Tiago\DesignPattern;

use Tiago\DesignPattern\Impostos\Imposto;
use Tiago\DesignPattern\Orcamento;


class CalculadoraImpostos
{
    public function calcula(Orcamento $orcamento, Imposto $imposto): float
    {
        return $imposto->calculaImposto($orcamento);
    }
}
