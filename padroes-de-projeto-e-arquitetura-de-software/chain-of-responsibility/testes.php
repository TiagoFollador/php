<?php

use Tiago\DesignPattern\CalculadoraDescontos;
use Tiago\DesignPattern\CalculadoraImpostos;
use Tiago\DesignPattern\Impostos\Icms;
use Tiago\DesignPattern\Orcamento;

require 'vendor/autoload.php';	

/*
$calculadora = new CalculadoraImpostos();

$orcamento = new Orcamento();
$orcamento->valor = 100;

echo $calculadora->calcula($orcamento, new Icms());

*/

$calculadora = new CalculadoraDescontos();

$orcamento = new Orcamento();    
$orcamento->valor = 600;
$orcamento->quantidadeItens = 7;

echo $calculadora->calculadoraDescontos($orcamento);    