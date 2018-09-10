<?php

namespace Contraption\Replicator\Contracts;


use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Container
 *
 * The replicator is a container for dependency injection.
 * But not service location.
 *
 * @package Contraption\Replicator
 */
interface Container
{
    /**
     * Bind a concrete to the container.
     *
     * If `$shared` is true, the concrete resolution will be stored so that the same instance
     * may be returned upon subsequent requests to the container.
     *
     * @param string $id
     * @param        $concrete
     * @param bool   $shared
     *
     * @throws \Contraption\Replicator\Exceptions\ContainerException
     */
    public function bind(string $id, $concrete, bool $shared = false): void;

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
    public function has($id);

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
    public function get($id);

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
    public function getWith(string $id, array $arguments, bool $fresh = false);

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
    public function make(string $class);

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
    public function makeWith(string $class, array $arguments, bool $fresh = false);
}