# Doctrine2 behavioral extensions

This package is ment to be a slight variation of the original [DoctrineExtensions](https://github.com/Atlantic18/DoctrineExtensions).

## Install

```
composer install evozon-php/doctrine-extensions
```

## Differences

#### SoftDeleteable

The original `SoftDeleteable` behavior was to use timestamp column that was either `NULL` or the date time of deletion.

While some RDBMs can index NULL-able values, some can not (like Oracle). This means it will always do a full table scan.

The modified `SoftDeleatable` behavior is to use a boolean (`true`/`false`) or equivalent numeric (`1`/`0`) value to represent the *soft* deleteable state.

##### Usage

Assuming you are using [DoctrineBundle](http://symfony.com/doc/master/bundles/DoctrineBundle/index.html) and [StofDoctrineExtensionsBundle](http://symfony.com/doc/master/bundles/StofDoctrineExtensionsBundle/index.html).

Add the filter to `config.yml`, or change the original `softdeleteable`:

```
orm:
    ...
    entity_managers:
        default:
            ...
            filters:
                softdeleteable:
                    class: EvozonPhp\SoftDeleteable\Filter\SoftDeleteableFilter
                    enabled: true
```

and add the `SoftDeleteableListener` to your `services.yml`:

```
services:
    evozonphp.listener.softdeleteable:
        class: EvozonPhp\SoftDeleteable\SoftDeleteableListener
        tags:
            - { name: doctrine.event_subscriber, connection: default }
        calls:
            - [ setAnnotationReader, [ "@annotation_reader" ] ]
```

# Contributors:

Thanks to [everyone participating](http://github.com/l3pp4rd/DoctrineExtensions/contributors) in the development of the Doctrine2 extensions!
