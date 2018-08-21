<?php

namespace Contraption\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class IndexController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $response = new Response;
        $response->getBody()->write('<h1>Hello, World!</h1>');

        return $response;
    }
}