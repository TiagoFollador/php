<?php

namespace Tiago\DesignPattern\Descontos;

use Tiago\DesignPattern\Orcamento;

class DesconoMaisQuinhentosReais extends Desconto
{
    public function calculaDesconto(Orcamento $orcamento): float
    {
        if ($orcamento->quantidadeItens > 500) {
            return $orcamento->valor * 0.05;
        }

        return $this->proximoDesconto->calculaDesconto($orcamento);
    }
}