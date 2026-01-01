<?php

it('has default config values', function () {
    expect(config('fortnox.base_url'))->toBe('https://api.fortnox.se/3/');
    expect(config('fortnox.oauth_base_url'))->toBe('https://apps.fortnox.se/oauth-v1');
    expect(config('fortnox.use_service_account'))->toBeTrue();
});

it('has oauth route configuration', function () {
    expect(config('fortnox.routes.oauth.redirect'))->toBe('/oauth/fortnox');
    expect(config('fortnox.routes.oauth.callback'))->toBe('/oauth/fortnox/callback');
});

it('has web middleware configured for routes', function () {
    expect(config('fortnox.routes.middleware'))->toContain('web');
});

it('has cache storage configured by default', function () {
    expect(config('fortnox.storage_provider'))
        ->toBe(\BernskioldMedia\Fortnox\TokenStorage\CacheTokenStorage::class);
});

it('has cache provider configuration', function () {
    expect(config('fortnox.provider_configuration.cache.prefix'))->toBe('fortnox.token');
    expect(config('fortnox.provider_configuration.cache.driver'))->toBeNull();
});
