<?php

$socket = stream_socket_server('tcp://localhost:8001');

$coneection = stream_socket_accept($socket);

$espere = rand(1, 5); 
sleep($espere);
fwrite($coneection, "Resposta do servidor socket, apos $espere segundos");
fclose($coneection);