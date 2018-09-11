# Contraption
An exercise in creating simple modern PHP frameworks.

Contraption is designed to be an exercise in creating a simple, modern, PSR compliant PHP framework. It's primary purpose
is to be both a learning experience, and the subject of an article series.

# Requirements
The below are the requirements for Contraption.

- PHP >= 7.2
- Extensions
    - ext-json
    - [ext-ds](http://www.php.net/manual/en/book.ds.php)

# Third Party
While I'm trying to avoid other packages when working on this project, it's not always possible. Below are a list of all third party packages.

- PSR
    - [psr/container](https://packagist.org/packages/psr/container) (Interfaces only)
    - [psr/http-server-handler](https://packagist.org/packages/psr/http-server-handler) (Interfaces only)
    - [psr/http-server-middleware](https://packagist.org/packages/psr/http-server-middleware) (Interfaces only)
    - [psr/http-message](https://packagist.org/packages/psr/http-message) (Interfaces only)
    - [psr/http-factory](https://packagist.org/packages/psr/http-factory) (Interfaces only)
- Polyfill
    - [php-ds/php-ds](https://packagist.org/packages/php-ds/php-ds) (Polyfill for the ext-ds extension)
- Router
    - [nikic/fast-route](https://packagist.org/packages/nikic/fast-route)
- Dev
    - [roave/security-advisories](https://packagist.org/packages/roave/security-advisories)
    - [filp/whoops](https://packagist.org/packages/filp/whoops) (Nice error reporting)
    - [phpunit/phpunit](https://packagist.org/packages/phpunit/phpunit) (For testing, obviously)
    - [symfony/var-dumper](https://packagist.org/packages/symfony/var-dumper) (So I can dump variables nicely)

# Components
Each component of Contraption is designed to be a standalone package that functions both as part of, and apart from Contraption.

| Component | Feature | Namespace |
|-----------|---------|-----------|
| [Replicator](#replicator) | Container | `Contraption\Replicator` |
| [Accumulator](#accumulator) | Collections | `Contraption\Accumulator` |
| [Conduit](#conduit) | Pipeline | `Contraption\Conduit` |
| [Regulator](#regulator) | Validation | `Contraption\Regulator` |

## Replicator
Replicating things since forever.

## Accumulator
The Accumulator contains all of Contraptions collections. The collections are split into two groups, sequences and maps.

These collections do not use arrays to store their items, instead they make use of the data structures provided by the `ext-ds` extension.

### Sequences
Sequence describes the behaviour of values arranged in a single, linear dimension. Some languages refer to this as a List. It’s similar to an array that uses incremental integer keys, with the exception of a few characteristics:

- Values will always be indexed as `[0, 1, 2, …, size - 1]`.
- Removing or inserting updates the position of all successive values.
- Only allowed to access values by index in the range `[0, size - 1]`.

 Collection | Description | Data Structure
 ---|---|---|
 `Contraption\Accumulator\Collection` | A super simple basic collection. | `Vector`

### Maps
A Map is a sequential collection of key-value pairs, almost identical to an array when used in a similar context. Keys can be any type, but must be unique. Values are replaced if added to the map using the same key.

Like an array, insertion order is preserved.

 Collection | Description | Data Structure
 ---|---|---|
 `Contraption\Accumulator\KeyedCollection` | A simple collection with keys. | `Map`
 `Contraption\Accumulator\FixedCollection` | A collection that only contains unique values. | `Set`

### Strict Mode
All collections can optionally have strict mode enabled. Strict mode on a collection will enforce a particular data type.

#### Sequences
For sequences you need only provide the value type.

    Contaners::basic()->strict('string')

#### Maps
For maps you need to provide both the key and value type.

    Containers::keyed()->strict('string', MyEntity::class)

### Normalise
All collections can optionally have a normaliser ran against new entries. The normaliser must be of type `callable`.

#### Sequences
For sequences you set the value normalisation.

    Containers::basic()->normaliseValues('strtolower')

#### Maps
For maps you can set a normaliser for keys and values.

    Containers::keyed()->normaliseKeys('strtoupper')->normaliseValues('ceil')

## Conduit
Smash those ducts.

## Regulator
I'm afraid you're not coming in.