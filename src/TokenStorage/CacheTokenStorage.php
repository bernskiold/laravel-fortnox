<?php

namespace BernskioldMedia\Fortnox\TokenStorage;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use Illuminate\Support\Facades\Cache;

class CacheTokenStorage implements TokenStorage
{

    protected string $cacheKey;

    protected int $expiresIn;

    public function __construct()
    {
        $this->cacheKey = config('fortnox.provider_configuration.cache.prefix', 'fortnox.token');
        $this->expiresIn = config('fortnox.provider_configuration.cache.expires_in', 60 * 60 * 24); // Default to 24 hours
    }

    /**
     * Store the token data for a specific tenant.
     */
    public function storeToken(string $token): void
    {
        Cache::driver(config('fortnox.provider_configuration.cache.driver', null))
            ->put(
                $this->cacheKey,
                $token,
                now()->addSeconds($this->expiresIn)
            );
    }

    /**
     * Get the token data for a specific tenant.
     */
    public function getToken(): ?string
    {
        return Cache::driver(config('fortnox.provider_configuration.cache.driver', null))->get($this->cacheKey);
    }

    /**
     * Delete the token data for a specific tenant.
     */
    public function deleteToken(): void
    {
        Cache::driver(config('fortnox.provider_configuration.cache.driver', null))->forget($this->cacheKey);
    }

    /**
     * Check if a token exists for a specific tenant.
     */
    public function hasToken(): bool
    {
        return Cache::driver(config('fortnox.provider_configuration.cache.driver', null))->has($this->cacheKey);
    }
}
