<?php

namespace BernskioldMedia\Fortnox;

use BernskioldMedia\Fortnox\Commands\RefreshFortnoxAccessToken;
use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use BernskioldMedia\Fortnox\Socialite\FortnoxSocialiteProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Arr;
use Laravel\Socialite\Contracts\Factory;
use RateLimiter;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use function config;
use function url;

class FortnoxServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-fortnox')
            ->hasRoute('web')
            ->hasConfigFile()
            ->hasCommand(RefreshFortnoxAccessToken::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('bernskioldmedia/laravel-fortnox');
            });
    }

    public function bootingPackage()
    {
        $socialite = $this->app->make(Factory::class);
        $socialite->extend('fortnox', function ($app) use ($socialite) {
            $config = Arr::get($app, 'config.fortnox');
            $this->protectAgainstInvalidConfiguration($config);

            return $socialite->buildProvider(FortnoxSocialiteProvider::class, [
                'client_id' => Arr::get($config, 'client_id'),
                'client_secret' => Arr::get($config, 'client_secret'),
                'redirect' => url(Arr::get($config, 'routes.oauth.callback')),
            ]);
        });

        RateLimiter::for('fortnox', fn() => Limit::perSecond(25, 5));
    }

    public function registeringPackage()
    {

        $this->app->bind(FortnoxClient::class, function () {
            $this->protectAgainstInvalidConfiguration(config('fortnox'));

            /**
             * @var TokenStorage $tokenStorage
             */
            $tokenStorage = app(config('fortnox.storage_provider'));

            return (new FortnoxClient(
                accessToken: $tokenStorage->getToken()->token,
                baseUrl: config('fortnox.base_url'),
            ));
        });

        $this->app->bind(Fortnox::class, function () {
            $this->protectAgainstInvalidConfiguration(config('fortnox'));
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
