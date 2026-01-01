<?php

use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;

it('can create missing client id exception', function () {
    $exception = InvalidConfiguration::missingClientId();

    expect($exception)->toBeInstanceOf(InvalidConfiguration::class);
    expect($exception->getMessage())->toContain('client ID');
});

it('can create missing client secret exception', function () {
    $exception = InvalidConfiguration::missingClientSecret();

    expect($exception)->toBeInstanceOf(InvalidConfiguration::class);
    expect($exception->getMessage())->toContain('client secret');
});

it('can create missing base url exception', function () {
    $exception = InvalidConfiguration::missingBaseUrl();

    expect($exception)->toBeInstanceOf(InvalidConfiguration::class);
    expect($exception->getMessage())->toContain('Base URL');
});

it('can create missing storage provider exception', function () {
    $exception = InvalidConfiguration::missingStorageProvider();

    expect($exception)->toBeInstanceOf(InvalidConfiguration::class);
    expect($exception->getMessage())->toContain('storage provider');
});

it('can create invalid storage configuration exception', function () {
    $exception = InvalidConfiguration::invalidStorageConfiguration('Custom error message');

    expect($exception)->toBeInstanceOf(InvalidConfiguration::class);
    expect($exception->getMessage())->toContain('Custom error message');
});
