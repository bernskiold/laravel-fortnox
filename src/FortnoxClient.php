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
    protected ?FortnoxOAuth $oauth = null;
    protected ?string $tenantId = null;
    protected bool $useOAuth = false;

    public function __construct(
        private string $clientSecret,
        private string $accessToken,
        private string $baseUrl,
        array $config = []
    ) {
        $this->useOAuth = $config['use_oauth'] ?? false;
        
        if ($this->useOAuth) {
            $this->oauth = new FortnoxOAuth($config);
            $this->tenantId = $config['tenant_id'] ?? null;
            
            if (empty($this->tenantId)) {
                throw InvalidConfiguration::missingTenantId();
            }
        }
        
        $this->setupRequest();
    }

    public static function fromConfig(array $config): static
    {
        return new static(
            $config['client_secret'], 
            $config['access_token'], 
            $config['base_url'],
            $config
        );
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

        if ($this->useOAuth && $this->oauth && $this->tenantId) {
            // If we're using OAuth and we have a token, use it
            if ($this->oauth->hasToken($this->tenantId)) {
                $accessToken = $this->oauth->getAccessToken($this->tenantId);
                $headers['Authorization'] = 'Bearer ' . $accessToken;
            }
        } else {
            // Otherwise, use the legacy authentication method
            $headers['Access-Token'] = $this->accessToken;
            $headers['Client-Secret'] = $this->clientSecret;
        }

        $this->request = Http::acceptJson()
            ->asJson()
            ->withHeaders($headers)
            ->baseUrl($this->baseUrl);
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
     * Get the OAuth manager.
     *
     * @return FortnoxOAuth|null
     */
    public function oauth(): ?FortnoxOAuth
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
        return $this->useOAuth && 
               $this->oauth && 
               $this->tenantId && 
               $exception->response->status() === 401;
    }

    /**
     * Refresh the OAuth token.
     *
     * @return void
     * @throws TokenRequestException
     */
    protected function refreshToken(): void
    {
        if (!$this->useOAuth || !$this->oauth || !$this->tenantId) {
            return;
        }

        $this->oauth->refreshToken($this->tenantId);
        $this->setupRequest();
    }
}
