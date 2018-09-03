<?php

namespace Contraption\Core\Container;

use Contraption\Core\Container\Dependencies\ClassResolver;
use Contraption\Core\Container\Dependencies\FunctionResolver;
use Contraption\Core\Container\Dependencies\MethodResolver;
use Contraption\Core\Container\Dependencies\ObjectResolver;
use Contraption\Core\Container\Dependencies\Resolver;
use Contraption\Core\Container\Exceptions\ContainerException;
use Contraption\Core\Container\Exceptions\NotFoundException;
use Ds\Map;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /**
     * @var \Contraption\Core\Container\Container
     */
    private static $instance;

    /**
     * Return an instance of the container.
     *
     * If no instance exists, create one.
     *
     * @return \Contraption\Core\Container\Container
     */
    public static function instance(): self
    {
        if (! (self::$instance instanceof self)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @var \Ds\Map
     */
    protected $entries;

    /**
     * Construct the container.
     *
     * This is private because there should only ever be one instance of the container.
     */
    private function __construct()
    {
        $this->entries = new Map();
    }

    /**
     * Bind a concrete to the container.
     *
     * If `$shared` is true, the concrete resolution will be stored so that the same instance
     * may be returned upon subsequent requests to the container.
     *
     * @param string $id
     * @param        $concrete
     * @param bool   $shared
     */
    public function bind(string $id, $concrete, bool $shared = false)
    {
        $dependency = $this->getDependencyResolver($concrete);

        if ($dependency) {
            $this->entries->put($id, $dependency
                ->setShared($shared));
        }
    }

    /**
     * Create a resolver for the given dependency.
     *
     * @param string|array|object|\Closure $dependency
     *
     * @return \Contraption\Core\Container\Dependencies\Resolver
     */
    private function getDependencyResolver($dependency): Resolver
    {
        // If it's a closure we want to return a function resolver.
        // We do this first so that it doesn't get caught by the is_object or is_callable check.
        if ($dependency instanceof \Closure) {
            return new FunctionResolver($dependency);
        }

        // If we have an object we return an object resolver.
        // An object resolver is basically a placeholder.
        if (\is_object($dependency)) {
            return new ObjectResolver($dependency);
        }

        // If it's callable there are a couple of possibilities.
        if (\is_callable($dependency)) {
            // If it's just a string, it returns a function resolver.
            if (\is_string($dependency)) {
                return new FunctionResolver($dependency);
            }

            // If it's an array of 2 values, it's an object/class/function => method situation.
            if (\is_array($dependency) && \count($dependency) === 2) {
                $dependency = array_values($dependency);

                // We want to return a method resolver but also grab a resolver for the first argument,
                // which can be anything covered by this method.
                return new MethodResolver($dependency[1], $this->getDependencyResolver($dependency[0]));
            }
        }

        // Finally if it's a string, and an existing class we want to return a class resolver.
        // This will internally create and call a method resolver if a constructor exists.
        if (\is_string($dependency) && class_exists($dependency)) {
            return new ClassResolver($dependency);
        }

        return null;
    }

    /**
     * Retrieve a resolver for the given identifier.
     *
     * @param string $id
     *
     * @return \Contraption\Core\Container\Dependencies\Resolver|null
     */
    private function getResolver(string $id): ?Resolver
    {
        return $this->entries->get($id, null);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->entries->hasKey($id);
    }

    /**
     * Resolve an entry of the container by its identifier.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        return $this->getWith($id, []);
    }

    /**
     * Resolve an entry from the container by its identifier, with the provided arguments.
     *
     * If the `$fresh` flag is provided, a new instance of the any shared entries will be returned.
     *
     * @param string $id
     * @param array  $arguments
     * @param bool   $fresh
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed
     */
    public function getWith(string $id, array $arguments, bool $fresh = false)
    {
        if ($this->has($id)) {
            try {
                $resolver = $this->getResolver($id);

                if ($resolver) {
                    return $resolver->resolve($fresh, $arguments);
                }
            } catch (\Exception $e) {
                throw new ContainerException(sprintf('Unable to resolve %s', $id), 0, $e);
            }
        }

        throw new NotFoundException(sprintf('No container entry for %s', $id));
    }

    /**
     * Resolve the given class or identifier.
     *
     * @param string $class
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed
     */
    public function make(string $class)
    {
        return $this->makeWith($class, []);
    }

    /**
     * Resolve the given class or identifier, with the provided arguments.
     *
     * If the `$fresh` flag is provided, a new instance of the any shared entries will be returned.
     *
     * @param string $class
     * @param array  $arguments
     * @param bool   $fresh
     *
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed
     */
    public function makeWith(string $class, array $arguments, bool $fresh = false)
    {
        if ($this->has($class)) {
            return $this->get($class);
        }

        try {
            $resolver = $this->getDependencyResolver($class);

            if ($resolver) {
                return $resolver->resolve($fresh, $arguments);
            }
        } catch (\Exception $e) {
        }

        throw new ContainerException(sprintf('Unable to resolve %s', $class), 0, $e ?? null);
    }
}