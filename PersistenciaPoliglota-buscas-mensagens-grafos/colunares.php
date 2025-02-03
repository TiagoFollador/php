<?php

declare(strict_types=1);


$cluster = Cassandra::cluster()
    ->withContractPoints('colunares')
    ->build();


$session = $cluster->connect('e_comerce');

var_dump($session);