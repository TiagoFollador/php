<?php

namespace Tiago\DesignPattern;

use Tiago\DesignPattern\Descontos\{
    DesconoMaisCincoItens,
    DesconoMaisQuinhentosReais,
    SemDesconto
};

class CalculadoraDescontos
{
    public function calculadoraDescontos(Orcamento $orcamento): float
    {
        $cadeiaDeDescontos = new DesconoMaisCincoItens(
            new DesconoMaisQuinhentosReais(
                new SemDesconto()
            )
        );

        return $cadeiaDeDescontos->calculaDesconto($orcamento);
    }
}
