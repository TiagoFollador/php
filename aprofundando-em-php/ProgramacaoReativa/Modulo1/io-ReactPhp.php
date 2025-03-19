<?php

use React\EventLoop\Factory;
use React\Stream\DuplexResourceStream;
use React\Stream\ReadableResourceStream;

require_once "vendor/autoload.php";

$loop = Factory::create();


$stream_list = [
    new ReadableResourceStream(stream_socket_client("tcp://localhost:8001"), $loop),
    new ReadableResourceStream(fopen("Arquivo1.txt", "r"), $loop),
    new ReadableResourceStream(fopen("Arquivo2.txt", "r"), $loop),
];

$readHttpResponse = function (string $data) {
    $posicaoFimHttp = strpos($data, "\r\n\r\n");
    if ($posicaoFimHttp !== false) {
        echo substr($data, $posicaoFimHttp + 4) . PHP_EOL;
    }
};

$http =    new DuplexResourceStream(stream_socket_client("tcp://localhost:8080"), $loop);
$http->write("GET /http-server.php HTTP/1.1" . "\r\n\r\n");
$http->on('data', $readHttpResponse);

$stream_list[0]->write("GET /http-server.php HTTP/1.1". "\r\n\r\n");

$function = function (string $data) {
    echo $data . PHP_EOL;
};


foreach ($readableStreamList as $readableStream) {
    $readableStream->on('data', $function);
}


$loop->run();
