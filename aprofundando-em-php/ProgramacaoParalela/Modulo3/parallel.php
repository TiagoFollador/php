<?php

require_once "vendor/autoload.php";

use parallel\RunTime;

echo " executando Tarefa demorada 1" . PHP_EOL;
sleep(10);
echo "Finalizando tarefa demorada 1" . PHP_EOL;

$runtime = new RunTime();


$runtime->run(function ()
{
    echo " executando Tarefa demorada 2" . PHP_EOL;
    sleep(5);
    echo "Finalizando tarefa demorada 2" . PHP_EOL;
});

$runtime2 = new RunTime();

$tarefa2 = function () {
    echo " executando Tarefa demorada 3" . PHP_EOL;
    sleep(8);
    echo "Finalizando tarefa demorada 3" . PHP_EOL;
};

$runtime2->run($tarefa2);

