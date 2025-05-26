<?php

namespace BernskioldMedia\Fortnox\OAuth\Contracts;

interface TokenStorage
{
    /**
     * Store the token data for a specific tenant.
     *
     * @param string $tenantId
     * @param array $tokenData
     * @return void
     */
    public function storeToken(string $tenantId, array $tokenData): void;

    /**
     * Get the token data for a specific tenant.
     *
     * @param string $tenantId
     * @return array|null
     */
    public function getToken(string $tenantId): ?array;

    /**
     * Delete the token data for a specific tenant.
     *
     * @param string $tenantId
     * @return void
     */
    public function deleteToken(string $tenantId): void;

    /**
     * Check if a token exists for a specific tenant.
     *
     * @param string $tenantId
     * @return bool
     */
    public function hasToken(string $tenantId): bool;
}

