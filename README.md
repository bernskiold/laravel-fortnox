# Laravel Fortnox

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bernskioldmedia/laravel-fortnox.svg?style=flat-square)](https://packagist.org/packages/bernskioldmedia/laravel-fortnox)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/bernskioldmedia/laravel-fortnox/run-tests?label=tests)](https://github.com/bernskioldmedia/laravel-fortnox/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/bernskioldmedia/laravel-fortnox/Check%20&%20fix%20styling?label=code%20style)](https://github.com/bernskioldmedia/laravel-fortnox/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bernskioldmedia/laravel-fortnox.svg?style=flat-square)](https://packagist.org/packages/bernskioldmedia/laravel-fortnox)

A Laravel package to consume the Fortnox API.

## Installation

You can install the package via composer:

```bash
composer require bernskioldmedia/laravel-fortnox
```

Publishing the config file is optional. You can publish the config file with:

```bash
php artisan vendor:publish --provider="Bernskiold\LaravelFortnox\LaravelFortnoxServiceProvider" --tag="config"
```

You need to set your Fortnox app credentials in your `.env` file. You can obtain these credentials by creating an
application in the Fortnox Developer Portal.

You also need to set which scopes you want to request access to. The scopes you can request are listed in the
[Fortnox API documentation](https://www.fortnox.se/developer/guides-and-good-to-know/scopes).

```dotenv
FORTNOX_CLIENT_ID=your-client-id
FORTNOX_CLIENT_SECRET=your-client-secret
FORTNOX_SCOPES=scope1,scope2,scope3
```

Finally, you should register the command that refreshes access tokens automatically.

In Laravel 12 and later, you register the command in your `routes/console.php` file:

```php
use Bernskiold\LaravelFortnox\Commands\RefreshAccessToken;
use Illuminate\Support\Facades\Schedule;

// Refresh the Fortnox access token if expired.
Schedule::call(RefreshAccessToken::class)->everyMinute()->withoutOverlapping();
```

In Laravel 11 and earlier, you can register the command in your `app/Console/Kernel.php` file:

```php
use Bernskiold\LaravelFortnox\Commands\RefreshAccessToken;

protected function schedule(Schedule $schedule)
{
    // Refresh the Fortnox access token if expired.
    $schedule->call(RefreshAccessToken::class)->everyMinute()->withoutOverlapping();
}
```

## Usage

### Basic Usage

You can use the provided `Fortnox` facade to access the support endpoints. For example, to get a list of invoices:

```php
use Bernskiold\LaravelFortnox\Facades\Fortnox;

// This will return a collection of invoices.
$invoices = Fortnox::invoices()->all()->get();
```

### Changing the token storage

By default the package will store the access token in the Laravel cache. However, you can change this storage provider,
either to one of the built-in providers or to a custom one.

To create a custom token storage provider, you need to implement the `Bernskiold\LaravelFortnox\Contracts\TokenStorage`
interface. You can then register your custom provider in the `fortnox.php` config file under the `storage_provider` key.

The build-in storage providers are:

- `Bernskiold\LaravelFortnox\StorageProviders\CacheTokenStorage`: Uses Laravel's cache to store the token.
- `Bernskiold\LaravelFortnox\StorageProviders\LaravelSettingsStorage`: Uses Spatie's Laravel Settings package to store
  the token.

Both of these providers offer configuration settings in the `fortnox.php` config file.

### Acting as user or application

The package supports both accessing the API as a Fortnox user or as a system application (via a service account).
By default the package will act as a service account, but you may change this by changing the `use_service_account`
option in the config file to `false`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bernskiold Media](https://github.com/bernskioldmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
