# AQI Desktop

A Mac taskbar app for monitoring the Air Quality Index in your zip code.

Requires an API key that you can obtain for free by registering at the [AirNow portal](https://docs.airnowapi.org/login?index=).

AQI category numbers, descriptions, and colors can be found at [AQI 101](https://docs.airnowapi.org/aq101). 

Made with [NativePHP](), [Folio](), and [Volt]().

## Install

Clone the repo, then run:

```shell
composer install
```

## Run in dev mode

```shell
npm run dev

php artisan native:serve
```

## Build

```shell
npm run build

php artisan native:build
```

## Database location

/Users/<user>/Library/Application\ Support/NativePHP/database/database.sqlite


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
