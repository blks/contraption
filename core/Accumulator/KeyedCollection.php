<?php

namespace Contraption\Accumulator;

use Ds\Collection as MapCollection;
use Ds\Map;

/**
 * Keyed Collection
 *
 * A collection with custom keys, work as a map, or a standard PHP array.
 *
 * @see     \Ds\Map
 *
 * @mixin \Ds\Map
 *
 * @method KeyedCollection apply(callable $callback)
 * @method KeyedCollection filter(?callable $callback = null)
 * @method KeyedCollection map(callable $callback)
 * @method KeyedCollection merge(array | \Traversable $values)
 * @method KeyedCollection reverse()
 * @method KeyedCollection reversed()
 * @method KeyedCollection slice(int $offset, ?int $length = null)
 * @method KeyedCollection sorted(?callable $comparator = null)
 * @method KeyedCollection copy()
 *
 * @package Contraption\Accumulator
 */
class KeyedCollection
{
    use Concerns\ReturnsNewSelf;

    /**
     * All underlying methods that should return a new instance of this
     * container with the items set.
     *
     * @var array
     */
    private static $newInstance = [
        'apply',
        'filter',
        'map',
        'merge',
        'reverse',
        'reversed',
        'slice',
        'sorted',
        'copy',
    ];

    /**
     * The map containing the underlying items.
     *
     * @var \Ds\Map
     */
    private $items;

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
        $this->items = new Map;
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
        if (method_exists($this->items, $name)) {
            $this->items->{$name}(...$arguments);
            return $this;
        }

        throw new \RuntimeException(sprintf('Method %s not present', $name));
    }

    private function setItems(MapCollection $map): self
    {
        $this->items = $map;
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
            } catch (\TypeError $e) {
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
                throw new \TypeError(sprintf('Value must be of type %s', $this->valueType));
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
        $this->items->put($key, $value);
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
        foreach ($this->items as $key => $value) {
            $callback($value, $key);
        }

        return $this;
    }

    /**
     * @param \Contraption\Accumulator\KeyedCollection $collection
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function diff(KeyedCollection $collection): self
    {
        return $this->newSelf($this->items->diff($collection->items));
    }

    /**
     * @param \Contraption\Accumulator\KeyedCollection $collection
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public function intersect(KeyedCollection $collection): self
    {
        return $this->newSelf($this->items->intersect($collection->items));
    }

    public function union(KeyedCollection $collection): self
    {
        return $this->newSelf($this->items->union($collection->items));
    }

    public function xor(KeyedCollection $collection): self
    {
        return $this->newSelf($this->items->xor($collection->items));
    }
}