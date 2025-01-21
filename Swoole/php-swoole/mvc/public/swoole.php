
<?php

require __DIR__ . '/../vendor/autoload.php';
ini_set('error_reporting', E_ALL);


use Nyholm\Psr7\ServerRequest;
use Swoole\Http\{Server, Response, Request};
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

Co::set(['hook_flags' => SWOOLE_HOOK_ALL]);

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/dependencias.php';
$rotas = require __DIR__ . '/../config/rotas.php';


$serverRequest = function (Request $request, Response $response) use ($container, $rotas) {


    $path =
        $request->server['PATH_INFO'] ?? '/';

    if ($path === '/') {
        $response->redirect('/listar-cursos');
        return;
    }

    if (!isset($rotas[$path])) {
        $response->setStatusCode(404);
        return;
    }

    
    if (session_status() === PHP_SESSION_ACTIVE 
        && array_key_exists(session_name(), $request->cookie)
        && session_id() !== $request->cookie[session_name()]) {
        session_id($request->cookie[session_name()]);
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_start();
    if (!isset($_SESSION['logado']) && stripos($path, 'login') === false) {
        $_SESSION['tipo_mensagem'] = 'danger';
        $_SESSION['mensagem_flash'] = 'Você não está logado';
        $response->redirect('/login');
        return;
    } 
        

    $controllerClass = $rotas[$path];
    $serverRequest = (new ServerRequest(
        $request->getMethod(),
        $request->server['request_url'],
        $request->header,
        $request->getData(),
        serverParams: $request->server,

    ))
        ->withQueryParams($request->get ?? [])
        ->withParsedBody($request->post ?? []);

    /** @var RequestHandlerInterface $controllerInstance */
    $controllerInstance = $container->get($controllerClass);

    $responsePsr7 = $controllerInstance->handle($serverRequest);

    foreach ($responsePsr7->getHeaders() as $header => $valores) {
        if ($header === 'Location') {
            $response->redirect($valores[0]);
            return; 
        }
        foreach ($valores as $value) {
            $response->header($header, $value);
        }
    }
     $response->end($responsePsr7->getBody());

};


$servidor = new Server('0.0.0.0', 8080);



$servidor->on('request', $serverRequest);

$servidor->start();
