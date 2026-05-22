# Installation

> [!NOTE]
> Coming from [`ryangjchandler/filament-navigation`](https://github.com/ryangjchandler/filament-navigation)? Please see
> the [upgrade guide](../UPGRADING.md#ryangjchandlerfilament-navigation-to-van-onsfilament-navigation) for more information.

Start by installing the package via Composer:

```bash
composer require van-ons/filament-navigation:^2.0
```

Next, run the migrations:

```sh
php artisan migrate
```

Finally, publish the package's assets:

```sh
php artisan filament:assets
```
