<?php

namespace BernskioldMedia\Fortnox\OAuth\Storage;

use BernskioldMedia\Fortnox\OAuth\Contracts\TokenStorage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class FileTokenStorage implements TokenStorage
{
    protected Filesystem $filesystem;
    protected string $storagePath;

    public function __construct(string $storagePath)
    {
        $this->filesystem = new Filesystem();
        $this->storagePath = $storagePath;

        if (! $this->filesystem->exists($this->storagePath)) {
            $this->filesystem->makeDirectory($this->storagePath, 0755, true);
        }
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
        $filePath = $this->getTokenFilePath($tenantId);
        $this->filesystem->put($filePath, json_encode($tokenData, JSON_PRETTY_PRINT));
    }

    /**
     * Get the token data for a specific tenant.
     *
     * @param string $tenantId
     * @return array|null
     */
    public function getToken(string $tenantId): ?array
    {
        $filePath = $this->getTokenFilePath($tenantId);

        if (! $this->filesystem->exists($filePath)) {
            return null;
        }

        $content = $this->filesystem->get($filePath);

        return json_decode($content, true);
    }

    /**
     * Delete the token data for a specific tenant.
     *
     * @param string $tenantId
     * @return void
     */
    public function deleteToken(string $tenantId): void
    {
        $filePath = $this->getTokenFilePath($tenantId);

        if ($this->filesystem->exists($filePath)) {
            $this->filesystem->delete($filePath);
        }
    }

    /**
     * Check if a token exists for a specific tenant.
     *
     * @param string $tenantId
     * @return bool
     */
    public function hasToken(string $tenantId): bool
    {
        return $this->filesystem->exists($this->getTokenFilePath($tenantId));
    }

    /**
     * Get the file path for a specific tenant's token.
     *
     * @param string $tenantId
     * @return string
     */
    protected function getTokenFilePath(string $tenantId): string
    {
        return $this->storagePath . '/' . $tenantId . '.json';
    }
}
