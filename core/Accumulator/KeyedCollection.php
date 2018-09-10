<?php

namespace Contraption\Accumulator;

use Ds\Map;

/**
 * Keyed Collection
 *
 * A collection with custom keys, work as a map, or a standard PHP array.
 *
 * @mixin \Ds\Map
 *
 * @package Contraption\Accumulator
 */
class KeyedCollection
{
    /**
     * The map containing the underlying items.
     *
     * @var \Ds\Map
     */
    private $map;

    /**
     * Whether or not strict mode is enabled.
     *
     * @var bool
     */
    private $strict = false;

    /**
     * The type for the key.
     *
     * @var string|null
     */
    private $keyType;

    /**
     * The type for the value.
     *
     * @var string|null
     */
    private $valueType;

    public function __construct()
    {
        $this->map = new Map;
    }

    /**
     * Map any remaining methods directly to the underlying map.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->map, $name)) {
            $this->map->{$name}(...$arguments);
            return $this;
        }

        throw new \RuntimeException(sprintf('Method %s not present', $name));
    }

    private function setMap(Map $map): self
    {
        $this->map = $map;
        return $this;
    }

    public function copy(): self
    {
        $collection = new static;
        /** @noinspection PhpParamsInspection */
        $collection->setMap($this->map->copy());

        return $collection;
    }

    /**
     * @param \Contraption\Accumulator\KeyedCollection $collection
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function diff(KeyedCollection $collection): self
    {
        $this->setMap($this->map->diff($collection->map));
        return $this;
    }

    /**
     * @param callable|null $callback
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function filter(callable $callback = null): self
    {
        $this->setMap($this->map->filter($callback));
        return $this;
    }

    /**
     * @param \Contraption\Accumulator\KeyedCollection $collection
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function intersect(KeyedCollection $collection): self
    {
        $this->setMap($this->map->intersect($collection->map));
        return $this;
    }

    /**
     * @param callable|null $comparator
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function ksorted(callable $comparator = null): self
    {
        $this->setMap($this->map->ksorted($comparator));
        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function map(callable $callback): self
    {
        $this->setMap($this->map->map($callback));
        return $this;
    }

    /**
     * @param $values
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function merge($values): self
    {
        $this->setMap($this->map->merge($values));
        return $this;
    }

    /**
     * Enable strict mode for this collection.
     *
     * @param string $keyType
     * @param string $valueType
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function strict(string $keyType, string $valueType): self
    {
        $this->keyType   = $keyType;
        $this->valueType = $valueType;
        $this->strict    = true;

        $this->each(function ($value, $key) {
            try {
                $this->validateInput($key, $value);
            } catch(\TypeError $e) {
                throw new \TypeError(sprintf('The current dataset is incompatiable with provided data types %s and %s', $this->keyType, $this->valueType));
            }
        });

        return $this;
    }

    /**
     * Check whether or not this collection is in strict mode.
     *
     * @return bool
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }

    /**
     * Validate that the provided key and value match the strict settings of
     * this collection.
     *
     * @param $key
     * @param $value
     */
    private function validateInput($key, $value): void
    {
        if ($this->strict) {
            $keyType    = \gettype($key);
            $valueType  = \gettype($value);
            $keyCheck   = ($keyType === $this->keyType) || ($keyType === 'object' && $key instanceof $this->keyType);
            $valueCheck = ($valueType === $this->valueType) || ($valueType === 'object' && $value instanceof $this->valueType);

            if (! $keyCheck) {
                throw new \TypeError(sprintf('Key must be of type %s', $this->keyType));
            }

            if (! $valueCheck) {
                throw new \TypeError(sprintf('Value must be of type %s', $this->keyType));
            }
        }
    }

    /**
     * Add a value to this collection using the provided key.
     *
     * @param $key
     * @param $value
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function put($key, $value): self
    {
        $this->validateInput($key, $value);
        $this->map->put($key, $value);
        return $this;
    }

    /**
     * Iterate through all items and run the provided callback.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function each(\Closure $callback): self
    {
        foreach ($this->map as $key => $value) {
            $callback($value, $key);
        }

        return $this;
    }
}