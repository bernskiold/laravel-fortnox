<?php

use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use BernskioldMedia\Fortnox\Socialite\FortnoxSocialiteProvider;
use Laravel\Socialite\Facades\Socialite;

it('resolves the fortnox socialite driver', function () {
    config()->set('fortnox.client_id', 'client-id');
    config()->set('fortnox.client_secret', 'client-secret');
    config()->set('fortnox.base_url', 'https://api.fortnox.se/3');

    expect(Socialite::driver('fortnox'))->toBeInstanceOf(FortnoxSocialiteProvider::class);
});

it('rejects an invalid configuration when resolving the fortnox socialite driver', function () {
    config()->set('fortnox.client_id', null);

    Socialite::driver('fortnox');
})->throws(InvalidConfiguration::class);
