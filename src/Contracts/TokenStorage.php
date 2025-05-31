<?php

namespace BernskioldMedia\Fortnox\Contracts;

use BernskioldMedia\Fortnox\Data\StoredToken;
use Laravel\Socialite\Two\Token;

interface TokenStorage
{
    /**
     * Store the token data for a specific tenant.
     */
    public function storeToken(Token $token): void;

    /**
     * Get the token data
     * */
    public function getToken(): ?StoredToken;

    /**
     * Delete the token data
     *
     * @return void
     */
    public function deleteToken(): void;

    /**
     * Check if a token exists
     *
     * @return bool
     */
    public function hasToken(): bool;

}
