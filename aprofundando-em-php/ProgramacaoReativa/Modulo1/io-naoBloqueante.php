<?php 

$stream_list = [
    stream_socket_client("tcp://localhost:8080"),
    stream_socket_client("tcp://localhost:8001"),
    fopen("Arquivo1.txt", "r"),
    fopen("Arquivo2.txt", "r"),
];

fwrite($stream_list[0], "GET /http-server.php HTTP/1.1". "\r\n" . "\r\n");

foreach ($stream_list as $stream) {
    stream_set_blocking($stream, false);
}

// arquivo ja esta pronto para ler? 

do {
    $copyReadStream = $stream_list; // realizo uma coppia
    $numeroDeStreams = stream_select($copyReadStream, // modifica a lista, pois passa como referencia
      $write, $except,0,200000);  // analisa se na lista tem algum arquivo ou stram pronto para ser lido
    if ($numeroDeStreams === 0) { // nao ha arquivos
        continue;
    }
    
    foreach ($copyReadStream as $key => $stream) {
        $conteudo = stream_get_contents($stream);
        $posicaoFimHttp = strpos($conteudo, "\r\n\r\n");
        if ($posicaoFimHttp !== false) {
            echo substr($conteudo, $posicaoFimHttp + 4) . PHP_EOL;
        } else {
            echo $conteudo . PHP_EOL;
        }
        echo fgets($stream) . PHP_EOL;
        unset($stream_list[$key]);
    }
} while (!empty($straeam_list));

echo "todos os streams foram lidos" . PHP_EOL;