<?php

namespace Contraption\Replicator\Dependencies;

class ObjectResolver extends Resolver
{
    public function __construct(object $object)
    {
        $this->setResolved($object);
    }

    public function resolve(bool $new = false, array $arguments = [])
    {
        if (! $this->isShared()) {
            return clone $this->getResolved();
        }

        return $this->getResolved();
    }
}