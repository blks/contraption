<?php

namespace Contraption\Core\Container\Dependencies;

class MethodResolver extends Resolver
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var \Contraption\Core\Container\Dependencies\Resolver
     */
    private $parent;

    public function __construct(string $method, Resolver $parent)
    {
        $this->method = $method;
        $this->parent = $parent;
    }

    /**
     * @param bool  $new
     * @param array $arguments
     *
     * @throws \ReflectionException
     */
    public function resolve(bool $new = false, array $arguments = [])
    {
        $parent = $this->parent->resolve();

        if ($parent) {
            $method = new \ReflectionMethod($parent, $this->method);
        }
    }
}