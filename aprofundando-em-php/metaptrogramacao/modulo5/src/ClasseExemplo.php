<?php

namespace Alura\Reflection;

final class ClasseExemplo implements \JsonSerializable
{
    #[Atributo(22)]
    public string $propriedadePublica = 'publica';
    protected string $propriedadeProtegida = 'protegida';
    private string $propriedadePrivada = 'privada';

    public function __construct()
    {
        echo 'Executando construtor de ' . __CLASS__;
    }

    /**
     * @param ClasseExemplo $mensagem
     * @param int $numero
     * @throws \Exception quando der problema
     */

    public function metodoPublico(ClasseExemplo $mensagem,int $numero = 7): void
    {
        echo 'Executando método público ' .  ' ' . $mensagem . ' ' . $numero . PHP_EOL;
    }

    protected function metodoProtegido(): int
    {
        echo 'Executando método protegido';
        return 1;
    }

    private function metodoPrivado(int $a): void
    {
        echo 'Executando método privado';
    }

    public function jsonSerialize() : array
    {
        return get_object_vars($this);
    }
}
