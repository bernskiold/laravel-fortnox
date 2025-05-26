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

You can publish the config file with:

```bash
php artisan vendor:publish --tag="fortnox-config"
```

If you plan to use the database storage for OAuth2 tokens, you should also publish and run the migrations:

```bash
php artisan vendor:publish --tag="fortnox-migrations"
php artisan migrate
```

## Configuration

This is the contents of the published config file:

```php
return [
    /**
     * The access token that Fortnox needs to authenticate.
     * You can use the built-in generation command to generate it.
     */
    'access_token' => env('FORTNOX_ACCESS_TOKEN', ''),

    /**
     * The client secret provided by Fortnox for your application.
     */
    'client_secret' => env('FORTNOX_CLIENT_SECRET', ''),

    /**
     * The client ID provided by Fortnox for your application.
     * Required for OAuth2 authentication.
     */
    'client_id' => env('FORTNOX_CLIENT_ID', ''),

    /**
     * The URL to the Fortnox API.
     * This package currently only supports version 3.
     */
    'base_url' => env('FORTNOX_BASE_URL', 'https://api.fortnox.se/3/'),

    /**
     * The URL to the Fortnox OAuth2 authorization server.
     */
    'auth_url' => env('FORTNOX_AUTH_URL', 'https://apps.fortnox.se/oauth-v1/auth'),

    /**
     * The URL to the Fortnox OAuth2 token endpoint.
     */
    'token_url' => env('FORTNOX_TOKEN_URL', 'https://apps.fortnox.se/oauth-v1/token'),

    /**
     * The redirect URI for the OAuth2 flow.
     * This must match the redirect URI registered in the Fortnox developer portal.
     */
    'redirect_uri' => env('FORTNOX_REDIRECT_URI', ''),

    /**
     * The scopes to request during the OAuth2 flow.
     * Available scopes: https://www.fortnox.se/developer/authorization
     */
    'scopes' => env('FORTNOX_SCOPES', ''),

    /**
     * The state parameter for the OAuth2 flow.
     * This is used to prevent CSRF attacks.
     */
    'state' => env('FORTNOX_STATE', ''),

    /**
     * Whether to use OAuth2 authentication instead of access token.
     */
    'use_oauth' => env('FORTNOX_USE_OAUTH', false),

    /**
     * The path where the OAuth2 tokens will be stored.
     * Only used when using file storage for tokens.
     */
    'token_storage_path' => env('FORTNOX_TOKEN_STORAGE_PATH', storage_path('app/fortnox-tokens')),

    /**
     * The storage driver to use for storing OAuth2 tokens.
     * Options: 'file', 'database', 'cache'
     */
    'token_storage' => env('FORTNOX_TOKEN_STORAGE', 'file'),

    /**
     * The database table to use for storing OAuth2 tokens.
     * Only used when token_storage is set to 'database'.
     */
    'token_table' => env('FORTNOX_TOKEN_TABLE', 'fortnox_tokens'),

    /**
     * The cache key prefix to use for storing OAuth2 tokens.
     * Only used when token_storage is set to 'cache'.
     */
    'token_cache_prefix' => env('FORTNOX_TOKEN_CACHE_PREFIX', 'fortnox_token_'),
];
```

## Usage

### Legacy Authentication

If you're using the legacy authentication method with an access token and client secret:

```php
// Using the facade
use BernskioldMedia\Fortnox\Facades\Fortnox;

// Get all customers
$customers = Fortnox::customers()->all();

// Get a specific customer
$customer = Fortnox::customers()->find('1');

// Create a new customer
$customer = Fortnox::customers()->create([
    'Name' => 'Test Customer',
    'Email' => 'test@example.com',
]);

// Update a customer
$customer = Fortnox::customers()->update('1', [
    'Name' => 'Updated Customer Name',
]);

// Delete a customer
Fortnox::customers()->delete('1');
```

### OAuth2 Authentication

To use OAuth2 authentication, you need to set `use_oauth` to `true` in your config file and provide the necessary OAuth2 configuration values.

#### Step 1: Generate an Authorization URL

```php
use BernskioldMedia\Fortnox\Facades\Fortnox;

// Generate a state parameter to prevent CSRF attacks
$state = Str::random(40);
session(['fortnox_oauth_state' => $state]);

// Generate the authorization URL
$authUrl = Fortnox::getAuthorizationUrl($state);

// Redirect the user to the authorization URL
return redirect()->away($authUrl);
```

#### Step 2: Handle the Callback

```php
use BernskioldMedia\Fortnox\Facades\Fortnox;

public function callback(Request $request)
{
    $code = $request->query('code');
    $state = $request->query('state');
    $expectedState = session('fortnox_oauth_state');
    
    // Use a unique identifier for the tenant (e.g., user ID, company ID)
    $tenantId = auth()->id();
    
    try {
        // Exchange the authorization code for an access token
        $tokenData = Fortnox::exchangeAuthorizationCode($code, $state, $expectedState, $tenantId);
        
        // Token exchange successful
        return redirect()->route('dashboard')->with('success', 'Successfully connected to Fortnox!');
    } catch (\Exception $e) {
        // Handle errors
        return redirect()->route('dashboard')->with('error', 'Failed to connect to Fortnox: ' . $e->getMessage());
    }
}
```

#### Step 3: Using the API with OAuth2

```php
use BernskioldMedia\Fortnox\Facades\Fortnox;

// Set the tenant ID for the current request
$tenantId = auth()->id();
$customers = Fortnox::forTenant($tenantId)->customers()->all();
```

#### Token Management

```php
// Check if a token exists for a tenant
if (Fortnox::hasToken($tenantId)) {
    // Token exists
}

// Manually refresh a token
$newTokenData = Fortnox::refreshToken($tenantId);

// Delete a token
Fortnox::deleteToken($tenantId);
```

## Available Resources

The following resources are available:

- `absenceTransactions()`
- `accounts()`
- `accountCharts()`
- `contracts()`
- `customers()`
- `financialYears()`
- `invoices()`
- `project()`
- `sie()`
- `suppliers()`
- `supplierInvoices()`
- `supplierInvoicePayments()`
- `vouchers()`

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
