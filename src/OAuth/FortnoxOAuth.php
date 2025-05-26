<?php

namespace BernskioldMedia\Fortnox\OAuth;

use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use BernskioldMedia\Fortnox\Exceptions\OAuth\InvalidAuthorizationCodeException;
use BernskioldMedia\Fortnox\Exceptions\OAuth\InvalidStateException;
use BernskioldMedia\Fortnox\Exceptions\OAuth\TokenRequestException;
use BernskioldMedia\Fortnox\OAuth\Contracts\TokenStorage;
use BernskioldMedia\Fortnox\OAuth\Storage\CacheTokenStorage;
use BernskioldMedia\Fortnox\OAuth\Storage\DatabaseTokenStorage;
use BernskioldMedia\Fortnox\OAuth\Storage\FileTokenStorage;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FortnoxOAuth
{
    protected TokenStorage $tokenStorage;
    protected array $config;

    public function __construct(array $config, ?TokenStorage $tokenStorage = null)
    {
        $this->config = $config;
        $this->tokenStorage = $tokenStorage ?? $this->resolveTokenStorage();
    }

    /**
     * Get the authorization URL for the OAuth2 flow.
     *
     * @param string|null $state
     * @param string|null $scope
     * @param array $additionalParams
     * @return string
     * @throws InvalidConfiguration
     */
    public function getAuthorizationUrl(?string $state = null, ?string $scope = null, array $additionalParams = []): string
    {
        $this->validateOAuthConfig();

        $state = $state ?? $this->config['state'] ?? Str::random(40);
        $scope = $scope ?? $this->config['scopes'] ?? '';

        $params = array_merge([
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'response_type' => 'code',
            'state' => $state,
            'scope' => $scope,
        ], $additionalParams);

        return $this->config['auth_url'] . '?' . http_build_query($params);
    }

    /**
     * Exchange an authorization code for an access token.
     *
     * @param string $code
     * @param string $state
     * @param string $expectedState
     * @param string $tenantId
     * @return array
     * @throws InvalidAuthorizationCodeException
     * @throws InvalidConfiguration
     * @throws InvalidStateException
     * @throws TokenRequestException
     */
    public function exchangeAuthorizationCode(
        string $code,
        string $state,
        string $expectedState,
        string $tenantId
    ): array {
        $this->validateOAuthConfig();

        if ($state !== $expectedState) {
            throw new InvalidStateException('Invalid state parameter. This could be a CSRF attack.');
        }

        try {
            $response = Http::asForm()->post($this->config['token_url'], [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->config['redirect_uri'],
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
            ]);

            if ($response->failed()) {
                throw new InvalidAuthorizationCodeException(
                    'Failed to exchange authorization code: ' . $response->body()
                );
            }

            $tokenData = $response->json();
            $this->tokenStorage->storeToken($tenantId, $tokenData);

            return $tokenData;
        } catch (RequestException $e) {
            throw new TokenRequestException(
                'Error requesting access token: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Refresh an access token using a refresh token.
     *
     * @param string $tenantId
     * @return array
     * @throws InvalidConfiguration
     * @throws TokenRequestException
     */
    public function refreshToken(string $tenantId): array
    {
        $this->validateOAuthConfig();

        $tokenData = $this->tokenStorage->getToken($tenantId);

        if (! $tokenData || empty($tokenData['refresh_token'])) {
            throw new TokenRequestException('No refresh token available for tenant: ' . $tenantId);
        }

        try {
            $response = Http::asForm()->post($this->config['token_url'], [
                'grant_type' => 'refresh_token',
                'refresh_token' => $tokenData['refresh_token'],
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
            ]);

            if ($response->failed()) {
                throw new TokenRequestException(
                    'Failed to refresh token: ' . $response->body()
                );
            }

            $newTokenData = $response->json();
            $this->tokenStorage->storeToken($tenantId, $newTokenData);

            return $newTokenData;
        } catch (RequestException $e) {
            throw new TokenRequestException(
                'Error refreshing token: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Get the access token for a specific tenant.
     *
     * @param string $tenantId
     * @return string|null
     */
    public function getAccessToken(string $tenantId): ?string
    {
        $tokenData = $this->tokenStorage->getToken($tenantId);

        return $tokenData['access_token'] ?? null;
    }

    /**
     * Check if a token exists for a specific tenant.
     *
     * @param string $tenantId
     * @return bool
     */
    public function hasToken(string $tenantId): bool
    {
        return $this->tokenStorage->hasToken($tenantId);
    }

    /**
     * Delete the token for a specific tenant.
     *
     * @param string $tenantId
     * @return void
     */
    public function deleteToken(string $tenantId): void
    {
        $this->tokenStorage->deleteToken($tenantId);
    }

    /**
     * Resolve the token storage implementation based on the configuration.
     *
     * @return TokenStorage
     */
    protected function resolveTokenStorage(): TokenStorage
    {
        $driver = $this->config['token_storage'] ?? 'file';

        return match ($driver) {
            'database' => new DatabaseTokenStorage($this->config['token_table'] ?? 'fortnox_tokens'),
            'cache' => new CacheTokenStorage($this->config['token_cache_prefix'] ?? 'fortnox_token_'),
            default => new FileTokenStorage($this->config['token_storage_path'] ?? storage_path('app/fortnox-tokens')),
        };
    }

    /**
     * Validate the OAuth2 configuration.
     *
     * @return void
     * @throws InvalidConfiguration
     */
    protected function validateOAuthConfig(): void
    {
        if (empty($this->config['client_id'])) {
            throw InvalidConfiguration::missingClientId();
        }

        if (empty($this->config['client_secret'])) {
            throw InvalidConfiguration::missingClientSecret();
        }

        if (empty($this->config['redirect_uri'])) {
            throw InvalidConfiguration::missingRedirectUri();
        }

        if (empty($this->config['auth_url'])) {
            throw InvalidConfiguration::missingAuthUrl();
        }

        if (empty($this->config['token_url'])) {
            throw InvalidConfiguration::missingTokenUrl();
        }
    }
}

