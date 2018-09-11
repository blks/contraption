<?php

namespace Contraption\Accumulator;

class Collections
{
    /**
     * Return a fresh instance of a keyed collection.
     *
     * Keyed collections have keys of any type, including objects. This is its
     * own collection as the majority of the others have strictly numerical keys.
     *
     * @return \Contraption\Accumulator\KeyedCollection
     */
    public static function keyed(): KeyedCollection
    {
        return new KeyedCollection();
    }

    /**
     * Return a fresh instance of the simple collection.
     *
     * The simple collection uses the Ds\Vector class internally and allows for
     * simple numerical key array like behaviour. Indexes shift with each removal,
     * and should not be relied on.
     *
     * @return \Contraption\Accumulator\Collection
     */
    public static function simple(): Collection
    {
        return new Collection;
    }
}