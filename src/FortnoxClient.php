<?php

namespace BernskioldMedia\Fortnox;

use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use BernskioldMedia\Fortnox\Exceptions\OAuth\TokenRequestException;
use BernskioldMedia\Fortnox\OAuth\FortnoxOAuth;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class FortnoxClient
{
    public PendingRequest $request;
    protected FortnoxOAuth $oauth;
    protected string $tenantId;
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->oauth = app(FortnoxOAuth::class);
        $this->tenantId = $config['tenant_id'] ?? 'default';
        
        $this->setupRequest();
    }

    /**
     * Set up the HTTP request with the appropriate headers.
     *
     * @return void
     * @throws TokenRequestException
     */
    protected function setupRequest(): void
    {
        $headers = [];

        // If we have a token for the current tenant, use it
        if ($this->oauth->hasToken($this->tenantId)) {
            $accessToken = $this->oauth->getAccessToken($this->tenantId);
            $headers['Authorization'] = 'Bearer ' . $accessToken;
        }

        $this->request = Http::acceptJson()
            ->asJson()
            ->withHeaders($headers)
            ->baseUrl($this->config['base_url']);
    }

    /**
     * Set the tenant ID for OAuth2 authentication.
     *
     * @param string $tenantId
     * @return $this
     * @throws TokenRequestException
     */
    public function forTenant(string $tenantId): self
    {
        $this->tenantId = $tenantId;
        $this->setupRequest();
        
        return $this;
    }

    /**
     * Get the current tenant ID.
     *
     * @return string
     */
    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    /**
     * Get the OAuth manager.
     *
     * @return FortnoxOAuth
     */
    public function oauth(): FortnoxOAuth
    {
        return $this->oauth;
    }

    /**
     * Make a GET request to the Fortnox API.
     *
     * @param string $endpoint
     * @param array $query
     * @return object
     * @throws RequestException
     * @throws TokenRequestException
     */
    public function get(string $endpoint, array $query = []): object
    {
        try {
            return $this->request
                ->get($endpoint, $query)
                ->throw()
                ->object();
        } catch (RequestException $e) {
            if ($this->shouldRefreshToken($e)) {
                $this->refreshToken();
                return $this->request
                    ->get($endpoint, $query)
                    ->throw()
                    ->object();
            }
            
            throw $e;
        }
    }

    /**
     * Get the raw contents of a response.
     *
     * @param string $endpoint
     * @param array $query
     * @return string
     * @throws RequestException
     * @throws TokenRequestException
     */
    public function contents(string $endpoint, array $query = []): string
    {
        try {
            return $this->request
                ->get($endpoint, $query)
                ->throw()
                ->body();
        } catch (RequestException $e) {
            if ($this->shouldRefreshToken($e)) {
                $this->refreshToken();
                return $this->request
                    ->get($endpoint, $query)
                    ->throw()
                    ->body();
            }
            
            throw $e;
        }
    }

    /**
     * Make a POST request to the Fortnox API.
     *
     * @param string $endpoint
     * @param array $data
     * @return object
     * @throws RequestException
     * @throws TokenRequestException
     */
    public function post(string $endpoint, array $data = []): object
    {
        try {
            return $this->request
                ->post($endpoint, $data)
                ->throw()
                ->object();
        } catch (RequestException $e) {
            if ($this->shouldRefreshToken($e)) {
                $this->refreshToken();
                return $this->request
                    ->post($endpoint, $data)
                    ->throw()
                    ->object();
            }
            
            throw $e;
        }
    }

    /**
     * Make a PUT request to the Fortnox API.
     *
     * @param string $endpoint
     * @param array $data
     * @return object
     * @throws RequestException
     * @throws TokenRequestException
     */
    public function put(string $endpoint, array $data = []): object
    {
        try {
            return $this->request
                ->put($endpoint, $data)
                ->throw()
                ->object();
        } catch (RequestException $e) {
            if ($this->shouldRefreshToken($e)) {
                $this->refreshToken();
                return $this->request
                    ->put($endpoint, $data)
                    ->throw()
                    ->object();
            }
            
            throw $e;
        }
    }

    /**
     * Make a DELETE request to the Fortnox API.
     *
     * @param string $endpoint
     * @param array $data
     * @return bool
     * @throws RequestException
     * @throws TokenRequestException
     */
    public function delete(string $endpoint, array $data = []): bool
    {
        try {
            return $this->request
                ->delete($endpoint, $data)
                ->throw()
                ->ok();
        } catch (RequestException $e) {
            if ($this->shouldRefreshToken($e)) {
                $this->refreshToken();
                return $this->request
                    ->delete($endpoint, $data)
                    ->throw()
                    ->ok();
            }
            
            throw $e;
        }
    }

    /**
     * Determine if we should refresh the token based on the exception.
     *
     * @param RequestException $exception
     * @return bool
     */
    protected function shouldRefreshToken(RequestException $exception): bool
    {
        return $exception->response->status() === 401;
    }

    /**
     * Refresh the OAuth token.
     *
     * @return void
     * @throws TokenRequestException
     */
    protected function refreshToken(): void
    {
        $this->oauth->refreshToken($this->tenantId);
        $this->setupRequest();
    }
}
