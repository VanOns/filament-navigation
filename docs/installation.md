# Installation

> [!NOTE]
> Coming from [`ryangjchandler/filament-navigation`](https://github.com/ryangjchandler/filament-navigation)? Please see
> the [upgrade guide](../UPGRADING.md#ryangjchandlerfilament-navigation-to-van-onsfilament-navigation) for more information.

## Compatibility

For certain Filament versions, changes have to be made that render the package backwards incompatible with the previous version.
Please see the table below to determine which version you need.

| Version      | Filament |
|--------------|----------|
| v1 (current) | <4.0     |

**Please note:** the `main` branch will always be the latest major version.

## Installation

Start by installing the package via Composer:

```bash
composer require van-ons/filament-navigation
```

Next, run the migrations:

```sh
php artisan migrate
```

Finally, publish the package's assets:

```sh
php artisan filament:assets
```
