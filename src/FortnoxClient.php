<?php

namespace BernskioldMedia\Fortnox;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FortnoxClient
{
    public PendingRequest $request;

    public function __construct(
        private string $clientSecret,
        private string $accessToken,
        private string $baseUrl
    ) {
        $this->request = Http::acceptJson()
            ->asJson()
            ->withHeaders([
                'Access-Token' => $this->accessToken,
                'Client-Secret' => $this->clientSecret,
            ])
            ->baseUrl($this->baseUrl);
    }

    public function get(string $endpoint, array $query = []): object
    {
        return $this->request
            ->get($endpoint, $query)
            ->throw()
            ->object();
    }

    public function contents(string $endpoint, array $query = []): string
    {
        return $this->request
            ->get($endpoint, $query)
            ->throw()
            ->body();
    }

    public function post(string $endpoint, array $data = []): object
    {
        return $this->request
            ->post($endpoint, $data)
            ->throw()
            ->object();
    }

    public function put(string $endpoint, array $data = []): object
    {
        return $this->request
            ->put($endpoint, $data)
            ->throw()
            ->object();
    }

    public function delete(string $endpoint, array $data = []): bool
    {
        return $this->request
            ->delete($endpoint, $data)
            ->throw()
            ->ok();
    }
}
