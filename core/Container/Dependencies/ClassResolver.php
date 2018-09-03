<?php

namespace Contraption\Core\Container\Dependencies;

use Contraption\Core\Container\Container;
use Ds\Vector;
use ReflectionParameter;

class ClassResolver extends Resolver
{
    /**
     * @var string
     */
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function resolve(bool $new = false, array $arguments = [])
    {
        if (! $new && $this->isResolved() && $this->isShared()) {
            return $this->getResolved();
        }

        $resolved = $this->resolveClass($arguments);

        if ($this->isShared()) {
            $this->setResolved($resolved);
        }

        return $resolved;
    }

    private function resolveClass(array $arguments)
    {
        try {
            $reflection  = new \ReflectionClass($this->class);
            $constructor = $reflection->getConstructor();
            $parameters  = $constructor->getParameters() ?? [];

            if (! $constructor || ! $parameters) {
                return $reflection->newInstance();
            }

            $constructorArguments = (new Vector($parameters))
                ->map(function (\ReflectionParameter $parameter) use ($arguments) {
                    return $this->resolveParameter($parameter, $arguments);
                });

            return $reflection->newInstanceArgs($constructorArguments);
        } catch (\ReflectionException $e) {
        }
    }

    private function resolveParameter(ReflectionParameter $parameter, array $arguments)
    {
        $container    = Container::instance();
        $type         = $parameter->getType();
        $argumentName = $parameter->getName();
        $argumentType = $type->getName() ?? null;

        if (isset($arguments[$argumentName])) {
            if ($argumentType && \gettype($arguments[$argumentName]) !== $argumentType) {
                throw new \InvalidArgumentException(sprintf('Argument %s is incorrect type', $argumentName));
            }

            return $argumentType[$argumentName];
        }

        if ($argumentType) {
            return $container->make($argumentType);
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