<?php

namespace Contraption\Core;

use Psr\Container\ContainerInterface;

class Contraption
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return $this
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }
}