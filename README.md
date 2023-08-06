# AQI Desktop

A Mac taskbar app for monitoring the Air Quality Index in your zip code.

Requires an API key that you can obtain for free by registering at the [AirNow portal](https://docs.airnowapi.org/login?index=).

AQI category numbers, descriptions, and colors can be found at [AQI 101](https://docs.airnowapi.org/aq101). 

Made with [NativePHP](), [Folio](), and [Volt]().

![AQI Desktop v1.0.0 Location](https://github.com/breadthe/aqi-desktop/assets/17433578/9a4d0188-bff7-4dfb-b88e-77f1a2ea3c55)

![AQI Desktop v1.0.0 History](https://github.com/breadthe/aqi-desktop/assets/17433578/4195a45c-97ab-43c9-8116-f2523a26a36c)

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

## License

This software is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
