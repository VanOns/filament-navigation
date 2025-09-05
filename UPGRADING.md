# Upgrading

We aim to make upgrading between versions as smooth as possible, but sometimes it involves specific steps to be taken.
This document will outline those steps. And as much as we try to cover all cases, we might miss some. If you come
across such a case, please let us know by [opening an issue][issues], or by adding it yourself and creating a pull request.

<!-- EXAMPLE -->
<!--
## v1 to v2

* Remove the `foo` column from the `bar` table.
* Add the `baz` column to the `bar` table.
* Run `php artisan migrate` to update the database.
-->

## `ryangjchandler/filament-navigation` to `van-ons/filament-navigation`

The package [`ryangjchandler/filament-navigation`](https://github.com/ryangjchandler/filament-navigation) was deprecated
on August 25th, 2025. We have forked the package and will continue its development under the new name: `van-ons/filament-navigation`.

To migrate to our package, please follow these steps:

1. Remove the old package:
    ```bash
    composer remove ryangjchandler/filament-navigation
    ```
2. Install the new package:
    ```bash
    composer require van-ons/filament-navigation
    ```
    Depending on your setup you may need to use a specific version. For more information, please refer to the
    [compatibility information](README.md#compatibility).
3. Replace all old namespaces with the new namespace:
    ```php
    # Old namespace
    use RyanChandler\FilamentNavigation\...;
    
    # New namespace
    use VanOns\FilamentNavigation\...;
    ```

[issues]: https://github.com/VanOns/filament-navigation/issues
