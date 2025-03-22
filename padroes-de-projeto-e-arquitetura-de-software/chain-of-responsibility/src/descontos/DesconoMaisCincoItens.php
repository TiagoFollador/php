<?php

namespace Tiago\DesignPattern\Descontos;

use Tiago\DesignPattern\Orcamento;

class DesconoMaisCincoItens extends Desconto
{
    public function calculaDesconto(Orcamento
     $orcamento): float
    {
        if ($orcamento->quantidadeItens > 5) {
            return $orcamento->valor * 0.1;
        }

        return $this->proximoDesconto->calculaDesconto($orcamento);

    }
}