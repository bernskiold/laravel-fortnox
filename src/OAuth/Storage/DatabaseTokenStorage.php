<?php

namespace BernskioldMedia\Fortnox\OAuth\Storage;

use BernskioldMedia\Fortnox\OAuth\Contracts\TokenStorage;
use Illuminate\Support\Facades\DB;

class DatabaseTokenStorage implements TokenStorage
{
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
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
        DB::table($this->table)->updateOrInsert(
            ['tenant_id' => $tenantId],
            [
                'access_token' => $tokenData['access_token'] ?? null,
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_at' => isset($tokenData['expires_in']) 
                    ? now()->addSeconds($tokenData['expires_in']) 
                    : null,
                'created_at' => now(),
                'updated_at' => now(),
                'token_data' => json_encode($tokenData),
            ]
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
        $token = DB::table($this->table)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $token) {
            return null;
        }

        return json_decode($token->token_data, true);
    }

    /**
     * Delete the token data for a specific tenant.
     *
     * @param string $tenantId
     * @return void
     */
    public function deleteToken(string $tenantId): void
    {
        DB::table($this->table)
            ->where('tenant_id', $tenantId)
            ->delete();
    }

    /**
     * Check if a token exists for a specific tenant.
     *
     * @param string $tenantId
     * @return bool
     */
    public function hasToken(string $tenantId): bool
    {
        return DB::table($this->table)
            ->where('tenant_id', $tenantId)
            ->exists();
    }
}

