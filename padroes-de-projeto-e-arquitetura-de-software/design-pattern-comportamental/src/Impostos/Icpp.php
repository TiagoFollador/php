<?php

namespace Tiago\DesignPattern\Impostos;

use Tiago\DesignPattern\Orcamento;

class Icpp implements Imposto
{
    public function calculaImposto(Orcamento $orcamento): float
    {
        if ($orcamento->valor > 500) {
            return $orcamento->valor * 0.03;
        }

        return $orcamento->valor * 0.02;
    }
}