<?php

\Co\run(function () {
    go(function () {
        Co::sleep(2);
        echo "apos 2 segundos" . PHP_EOL;
    });

    go(function () {
        Co::sleep(1);
        echo "apos 1 segundo". PHP_EOL;
    });
});