<?php

$espera = rand(1, 5);
sleep($espera);

echo "Resposta do servidor que levou $espera segundos" . PHP_EOL;
