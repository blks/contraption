# Contraption
An exercise in creating simple modern PHP frameworks.

Contraption is designed to be an exercise in creating a simple, modern, PSR compliant PHP framework. It's primary purpose
is to be both a learning experience, and the subject of an article series.

# Requirements
The below are the requirements for Contraption.

- PHP >= 7.2
- Extensions
    - ext-json
    - ext-ds

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
Accumulate all the things.

## Conduit
Smash those ducts.

## Regulator
I'm afraid you're not coming in.