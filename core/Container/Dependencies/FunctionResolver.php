<?php

namespace Contraption\Core\Container\Dependencies;

class FunctionResolver extends Resolver
{
    /**
     * @var string|\Closure
     */
    private $function;

    public function __construct($function)
    {
        $this->function = $function;
    }

    public function resolve(bool $new = false, array $arguments = [])
    {
        if (! $new && $this->isResolved() && $this->isShared()) {
            return $this->getResolved();
        }

        $resolved = \call_user_func_array($this->function, $arguments);

        if ($this->isShared()) {
            $this->setResolved($resolved);
        }

        return $resolved;
    }
}