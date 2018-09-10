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
}