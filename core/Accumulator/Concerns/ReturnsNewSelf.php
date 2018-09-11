<?php

namespace Contraption\Accumulator\Concerns;

trait ReturnsNewSelf
{
    public function __call($name, $arguments)
    {
        if (method_exists($this->items, $name)) {
            if (\in_array($name, self::$newInstance, true)) {
                return $this->newSelf($this->items->{$name}(...$arguments));
            }

            return $this->items->{$name}(...$arguments) ?? $this;
        }

        throw new \RuntimeException(sprintf('Method %s not present', $name));
    }

    protected function newSelf($items)
    {
        return (new static)->setItems($items);
    }
}