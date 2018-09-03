<?php

namespace Contraption\Core\Container\Dependencies;

class CallableResolver extends Resolver
{
    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }
}