<?php

namespace BernskioldMedia\Fortnox\OAuth\Storage;

use BernskioldMedia\Fortnox\OAuth\Contracts\TokenStorage;
use Illuminate\Support\Facades\Cache;

class CacheTokenStorage implements TokenStorage
{
    protected string $cachePrefix;

    public function __construct(string $cachePrefix)
    {
        $this->cachePrefix = $cachePrefix;
    }

    /**
     * Store the token data for a specific tenant.
     *
     * @param string $tenantId
     * @param array $tokenData
     * @return void
     */
    public function storeToken(string $tenantId, array $tokenData): void
    {
        $expiresIn = $tokenData['expires_in'] ?? 3600; // Default to 1 hour

        Cache::put(
            $this->getCacheKey($tenantId),
            $tokenData,
            now()->addSeconds($expiresIn)
        );
    }

    /**
     * Get the token data for a specific tenant.
     *
     * @param string $tenantId
     * @return array|null
     */
    public function getToken(string $tenantId): ?array
    {
        return Cache::get($this->getCacheKey($tenantId));
    }

    /**
     * Delete the token data for a specific tenant.
     *
     * @param string $tenantId
     * @return void
     */
    public function deleteToken(string $tenantId): void
    {
        Cache::forget($this->getCacheKey($tenantId));
    }

    /**
     * Check if a token exists for a specific tenant.
     *
     * @param string $tenantId
     * @return bool
     */
    public function hasToken(string $tenantId): bool
    {
        return Cache::has($this->getCacheKey($tenantId));
    }

    /**
     * Get the cache key for a specific tenant.
     *
     * @param string $tenantId
     * @return string
     */
    protected function getCacheKey(string $tenantId): string
    {
        return $this->cachePrefix . $tenantId;
    }
}
