<?php

use React\EventLoop\Factory;

require_once "vendor/autoload.php";

$loop = Factory::create();

$func = function () {
    echo "1 segundinho" . PHP_EOL;
};

$loop->addPeriodicTimer(1, $func);

$loop->run();