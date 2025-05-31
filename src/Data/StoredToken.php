<?php

namespace BernskioldMedia\Fortnox\Data;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Laravel\Socialite\Two\Token;
use Serializable;

class StoredToken implements Arrayable, Serializable
{
    public function __construct(
        public string          $token,
        public string          $refreshToken,
        public CarbonInterface $expiresAt,
    )
    {
    }

    public function toArray()
    {
        return [
            'token' => $this->token,
            'refresh_token' => $this->refreshToken,
            'expires_at' => $this->expiresAt->toIso8601String(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            token: $data['token'],
            refreshToken: $data['refresh_token'],
            expiresAt: Carbon::parse($data['expires_at'])
        );
    }

    public function serialize()
    {
        return serialize([
            'token' => $this->token,
            'refresh_token' => $this->refreshToken,
            'expires_at' => $this->expiresAt->toIso8601String(),
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized, ['allowed_classes' => false]);
        $this->token = $data['token'];
        $this->refreshToken = $data['refresh_token'];
        $this->expiresAt = Carbon::parse($data['expires_at']);
    }

    public function __serialize(): array
    {
        return [
            'token' => $this->token,
            'refresh_token' => $this->refreshToken,
            'expires_at' => $this->expiresAt->toIso8601String(),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->token = $data['token'];
        $this->refreshToken = $data['refresh_token'];
        $this->expiresAt = Carbon::parse($data['expires_at']);
    }

    public static function fromSocialiteToken(Token $token): self
    {
        return new self(
            token: $token->token,
            refreshToken: $token->refreshToken,
            expiresAt: Carbon::now()->addSeconds($token->expiresIn)
        );
    }

    public static function fromSerialized(string $serialized): self
    {
        $data = unserialize($serialized, ['allowed_classes' => false]);

        return new self(
            token: $data['token'],
            refreshToken: $data['refresh_token'],
            expiresAt: Carbon::parse($data['expires_at'])
        );
    }
}
