<?php

namespace BernskioldMedia\Fortnox;

use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use BernskioldMedia\Fortnox\OAuth\Contracts\TokenStorage;
use BernskioldMedia\Fortnox\OAuth\FortnoxOAuth;
use BernskioldMedia\Fortnox\OAuth\Storage\CacheTokenStorage;
use BernskioldMedia\Fortnox\OAuth\Storage\DatabaseTokenStorage;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FortnoxServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-fortnox')
            ->hasConfigFile()
            ->hasMigration('create_fortnox_tokens_table');
    }

    public function packageRegistered()
    {
        $this->registerTokenStorage();
    }

    public function registeringPackage()
    {
        $this->app->bind(FortnoxClient::class, function () {
            $config = config('fortnox');
            $this->protectAgainstInvalidConfiguration($config);
            
            return new FortnoxClient($config);
        });

        $this->app->bind(Fortnox::class, function () {
            $client = app(FortnoxClient::class);
            return new Fortnox($client);
        });

        $this->app->bind(FortnoxOAuth::class, function () {
            return new FortnoxOAuth(config('fortnox'), app(TokenStorage::class));
        });

        $this->app->alias(Fortnox::class, 'laravel-fortnox');
    }

    protected function registerTokenStorage()
    {
        $config = config('fortnox');
        $driver = $config['token_storage'] ?? 'database';

        $this->app->bind(TokenStorage::class, function () use ($driver, $config) {
            return match ($driver) {
                'cache' => new CacheTokenStorage($config['token_cache_prefix'] ?? 'fortnox_token_'),
                default => new DatabaseTokenStorage($config['token_table'] ?? 'fortnox_tokens'),
            };
        });
    }

    protected function protectAgainstInvalidConfiguration(array $config): void
    {
        if (empty($config['client_id'])) {
            throw InvalidConfiguration::missingClientId();
        }

        if (empty($config['client_secret'])) {
            throw InvalidConfiguration::missingClientSecret();
        }

        if (empty($config['redirect_uri'])) {
            throw InvalidConfiguration::missingRedirectUri();
        }

        if (empty($config['auth_url'])) {
            throw InvalidConfiguration::missingAuthUrl();
        }

        if (empty($config['token_url'])) {
            throw InvalidConfiguration::missingTokenUrl();
        }

        if (empty($config['base_url'])) {
            throw InvalidConfiguration::missingBaseUrl();
        }
    }
}
