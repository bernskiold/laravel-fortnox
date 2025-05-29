<?php

namespace BernskioldMedia\Fortnox\Contracts;

interface TokenStorage
{
    /**
     * Store the token data for a specific tenant.
     *
     * @param string $token
     * @return void
     */
    public function storeToken(string $token): void;

    /**
     * Get the token data for a specific tenant.
     *
     * @return string|null
     */
    public function getToken(): ?string;

    /**
     * Delete the token data for a specific tenant.
     *
     * @return void
     */
    public function deleteToken(): void;

    /**
     * Check if a token exists for a specific tenant.
     *
     * @return bool
     */
    public function hasToken(): bool;

}
