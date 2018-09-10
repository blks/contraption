<?php

namespace Contraption\Replicator\Dependencies;

use Contraption\Replicator\Container;
use Ds\Vector;
use ReflectionParameter;

class MethodResolver extends Resolver
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var \Contraption\Replicator\Dependencies\Resolver
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
     * @return mixed
     * @throws \ReflectionException
     */
    public function resolve(bool $new = false, array $arguments = [])
    {
        $parent = $this->parent->resolve();

        if ($parent) {
            $method = new \ReflectionMethod($parent, $this->method);
            $parameters  = $method ? $method->getParameters() : [];
            $parent = $this->parent->resolve($new, $arguments);

            if (! $parameters) {
                return $method->invoke($parent);
            }

            $arguments = (new Vector($parameters))
                ->map(function (\ReflectionParameter $parameter) use ($arguments) {
                    return $this->resolveParameter($parameter, $arguments);
                })
                ->toArray();

            return $method->invokeArgs($parent, $arguments);
        }
    }

    private function resolveParameter(ReflectionParameter $parameter, array $arguments)
    {
        $container    = Container::instance();
        $type         = $parameter->getType();
        $argumentName = $parameter->getName();
        $argumentType = $type ? $type->getName() : null;

        if (isset($arguments[$argumentName])) {
            if ($argumentType && \gettype($arguments[$argumentName]) !== $argumentType) {
                throw new \InvalidArgumentException(sprintf('Argument %s is incorrect type', $argumentName));
            }

            return $arguments[$argumentName];
        }

        if ($argumentType) {
            $argument = $container->make($argumentType);

            if ($argument) {
                return $argument;
            }
        }

        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull()) {
            return null;
        }

        throw new \InvalidArgumentException(sprintf('Unable to resolve argument %s', $argumentName));
    }
}