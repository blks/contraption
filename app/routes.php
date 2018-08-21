<?php
use League\Route\Router;

/**
 * @var Router $router
 */

$router->get('/', \Contraption\App\Controllers\IndexController::class);