<?php

namespace Contraption\Core\Container\Dependencies;

abstract class Resolver
{
    /**
     * @var bool
     */
    private $shared = false;

    /**
     * @var mixed
     */
    private $resolved;

    /**
     * @return bool
     */
    public function isShared(): bool
    {
        return $this->shared;
    }

    /**
     * @param bool $shared
     *
     * @return $this
     */
    public function setShared(bool $shared): self
    {
        $this->shared = $shared;
        return $this;
    }

    protected function isResolved(): bool
    {

        return $this->resolved !== null;
    }

    /**
     * @return mixed
     */
    protected function getResolved()
    {
        return $this->resolved;
    }

    /**
     * @param mixed $resolved
     *
     * @return $this
     */
    protected function setResolved($resolved): self
    {
        $this->resolved = $resolved;
        return $this;
    }

    abstract public function resolve(bool $new = false, array $arguments = []);
}