<?php

namespace BernskioldMedia\Fortnox;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FortnoxServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-fortnox')
            ->hasRoute('web')
            ->hasConfigFile();
    }

    public function registeringPackage()
    {
        $this->protectAgainstInvalidConfiguration(config('fortnox'));

        $this->app->bind(FortnoxClient::class, function () {
            /**
             * @var TokenStorage $tokenStorage
             */
            $tokenStorage = app(config('fortnox.storage_provider'));

            return (new FortnoxClient(
                clientSecret: config('fortnox.client_secret'),
                accessToken: $tokenStorage->getToken(),
                baseUrl: config('fortnox.base_url'),
            ));
        });

        $this->app->bind(Fortnox::class, function () {
            $client = app(FortnoxClient::class);

            return new Fortnox($client);
        });

        $this->app->alias(Fortnox::class, 'laravel-fortnox');
    }

    protected function protectAgainstInvalidConfiguration(array $config): void
    {
        if (empty($config['storage_provider'])) {
            throw InvalidConfiguration::missingStorageProvider();
        }

        if (empty($config['client_id'])) {
            throw InvalidConfiguration::missingClientId();
        }

        if (empty($config['client_secret'])) {
            throw InvalidConfiguration::missingClientSecret();
        }

        if (empty($config['base_url'])) {
            throw InvalidConfiguration::missingBaseUrl();
        }
    }
}
