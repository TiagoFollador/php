<?php

require __DIR__ . '/vendor/autoload.php';

use Array\Curso\Aluno;
use Array\Curso\Curso;

$alunoRepetido = new Aluno('Aluno 17');

$curso = new Curso('Collections PHP');
$curso->adicionaAlteracao('Primeira alteração');
$curso->adicionaAlteracao('Segunda alteração');
$curso->adicionaAlteracao('Terceira alteração');

foreach ($curso->recuperaAlteracoes() as $alteracao) {
    echo $alteracao . PHP_EOL;
}

$curso->adicionaAlunoParaEspera(new Aluno('Aluno 1'));
$curso->adicionaAlunoParaEspera(new Aluno('Aluno 2'));
$curso->adicionaAlunoParaEspera(new Aluno('Aluno 3'));

foreach ($curso->recuperaAlunosEsperando() as $aluno) {
    echo $aluno->name . PHP_EOL;
}

$curso->matriculaAluno(new Aluno('Aluno 7'));
$curso->matriculaAluno($alunoRepetido);
$curso->matriculaAluno($alunoRepetido);

foreach ($curso->recuperaAlunosMatriculados() as $aluno) {
    echo $aluno->name . PHP_EOL;
}