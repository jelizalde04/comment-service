<?php

require __DIR__ . '/vendor/autoload.php';

use React\Http\Server;
use React\Http\Response;
use React\EventLoop\Factory;
use Psr\Http\Message\ServerRequestInterface;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\GroupCountBased;

$loop = Factory::create();

$routes = require __DIR__ . '/routes/api.php';

$dispatcher = new Dispatcher\GroupCountBased(new RouteCollector(new Std(), new GroupCountBased()));
foreach ($routes as $route) {
    $dispatcher->addRoute($route[0], $route[1], $route[2]);
}

$server = new Server($loop, function (ServerRequestInterface $request) use ($dispatcher) {
    // Enable CORS
    if ($request->getMethod() === 'OPTIONS') {
        return new Response(200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);
    }

    $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

    switch ($routeInfo[0]) {
        case Dispatcher::NOT_FOUND:
            return new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Not Found']));
        case Dispatcher::METHOD_NOT_ALLOWED:
            return new Response(405, ['Content-Type' => 'application/json'], json_encode(['error' => 'Method Not Allowed']));
        case Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            return $handler($request, $vars);
    }
});

$socket = new \React\Socket\Server('0.0.0.0:7002', $loop);
$server->listen($socket);

echo "Server running at http://0.0.0.0:7002\n";

$loop->run();