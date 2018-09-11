<?php

namespace Contraption\Accumulator;

use Ds\Sequence;
use Ds\Vector;

/**
 * Collection
 *
 * A basic collection with automated numerical keys. Keys shouldn't be
 * relied on as changes to the values will cause the keys to change.
 *
 * @see     \Ds\Vector
 *
 * @mixin Vector
 *
 * @method Collection filter(?callable $callback = null)
 * @method Collection apply(callable $callback)
 * @method Collection map(callable $callback)
 * @method Collection merge(callable $callback)
 * @method Collection slice(callable $callback)
 * @method Collection sorted(callable $callback)
 * @method Collection copy()
 * @method Collection reversed()
 * @method Collection clear()
 * @method Collection offsetSet($offset)
 * @method Collection offsetUnset($offset)
 * @method Collection push(...$values)
 * @method Collection reverse()
 * @method Collection rotate(int $rotations)
 * @method Collection set(int $index, $value)
 * @method Collection sort(?callable $comparator = null)
 * @method Collection unshift(...$values)
 * @method Collection pushAll($values);
 *
 * @package Contraption\Accumulator
 */
class Collection
{
    use Concerns\ReturnsNewSelf,
        Concerns\AllowsStrictValues;

    /**
     * All underlying methods that should return a new instance of this
     * container with the items set.
     *
     * @var array
     */
    private static $newInstance = [
        'filter',
        'apply',
        'map',
        'merge',
        'slice',
        'sorted',
        'copy',
        'reversed',
    ];

    /**
     * @var \Ds\Vector
     */
    private $items;

    public function __construct()
    {
        $this->items = new Vector;
    }

    private function setItems(Sequence $vector): self
    {
        $this->items = $vector;
        return $this;
    }

    /**
     * Iterate through all items and run the provided callback.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function each(callable $callback): self
    {
        foreach ($this->items as $key => $value) {
            $callback($value, $key);
        }

        return $this;
    }
}