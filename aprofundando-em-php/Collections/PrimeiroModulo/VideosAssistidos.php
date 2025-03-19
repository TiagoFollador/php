<?php

require __DIR__ . '/vendor/autoload.php';


use Array\Curso\Video;

$video1 = new Video('Video 1');
$video2 = new Video('Video 2');

$videosAssistidos = [
    spl_object_hash($video1) => new DateTimeImmutable('2022-01-01'),
    spl_object_hash($video2) => new DateTimeImmutable('2024-02-01'),
];

echo $videosAssistidos[spl_object_hash($video1)]->format('d/m/Y') . PHP_EOL;
