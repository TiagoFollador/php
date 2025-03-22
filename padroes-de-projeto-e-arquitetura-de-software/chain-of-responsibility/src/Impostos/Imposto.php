<?php

namespace Tiago\DesignPattern\Impostos;

use Tiago\DesignPattern\Orcamento;

interface Imposto
{
    public function calculaImposto(Orcamento $orcamento): float;
}