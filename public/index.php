<?php
declare(strict_types=1);

// Include composer autoloader
use Contraption\Core\Contraption;
use League\Container\Container;
use League\Route\Router;

require __DIR__ . '/../vendor/autoload.php';

$contraption = new Contraption();
$container   = new Container();

$contraption->setContainer($container);

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$container->share(\Psr\Http\Message\ServerRequestInterface::class, $request);

$router = new Router;
require __DIR__ . '/../app/routes.php';

$response = $router->dispatch($request);
(new \Zend\Diactoros\Response\SapiEmitter)->emit($response);