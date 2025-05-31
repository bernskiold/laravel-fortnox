<?php

namespace BernskioldMedia\Fortnox\TokenStorage;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use BernskioldMedia\Fortnox\Data\StoredToken;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Two\Token;

class CacheTokenStorage implements TokenStorage
{

    protected string $cacheKey;

    protected ?string $driver;

    public function __construct()
    {
        $this->cacheKey = config('fortnox.provider_configuration.cache.prefix', 'fortnox.token');
        $this->driver = config('fortnox.provider_configuration.cache.driver', null);
    }

    /**
     * Store the token data.
     */
    public function storeToken(Token $token): void
    {
        $storedToken = StoredToken::fromSocialiteToken($token);
        Cache::driver($this->driver)->put($this->cacheKey, $storedToken->toArray(), now()->addSeconds($token->expiresIn));
    }

    /**
     * Get the token data.
     */
    public function getToken(): ?StoredToken
    {
        $tokenData = Cache::driver($this->driver)->get($this->cacheKey);

        if(!$tokenData) {
            return null;
        }

        return StoredToken::fromArray($tokenData);
    }

    /**
     * Delete the token data.
     */
    public function deleteToken(): void
    {
        Cache::driver($this->driver)->forget($this->cacheKey);
    }

    /**
     * Check if a token exists.
     */
    public function hasToken(): bool
    {
        return Cache::driver($this->driver)->has($this->cacheKey);
    }
}
