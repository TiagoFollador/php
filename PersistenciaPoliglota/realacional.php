<?php

declare(strict_types=1);

$pdo = new PDO(
    'pgsql:host=relacional;dbname=postgres',
    'postgres',
    'senha_postgres'
);

var_dump($pdo);