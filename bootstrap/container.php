<?php
/*
 * In here we add all of the default bindings required by the running of the system.
 * This is where we'd add change our PSR implementations.
 */

use Contraption\Core\Http\ServerRequestFactory;
use League\Container\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;

$container = new Container();

// Here we add some of the default bindings

/*
 * HTTP bindings
 */
$container->share(ContainerInterface::class, $container);
$container->add(ServerRequestFactoryInterface::class, ServerRequestFactory::class);
$container->add(Psr\Http\Message\RequestInterface::class, function (ServerRequestFactoryInterface $requestFactory) {

});

return $container;