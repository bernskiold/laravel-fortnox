<?php

namespace BernskioldMedia\Fortnox\Socialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\Token;
use Laravel\Socialite\Two\User;
use function base64_encode;
use function config;
use function explode;
use function json_decode;

class FortnoxSocialiteProvider extends AbstractProvider implements ProviderInterface
{

    protected $scopeSeparator = ' ';

    protected function getAuthUrl($state)
    {
        $baseUrl = config('fortnox.oauth_base_url', 'https://apps.fortnox.se/oauth-v1');

        return $this->buildAuthUrlFromBase("$baseUrl/auth", $state);
    }

    protected function getBaseUrl()
    {
        return config('fortnox.base_url');
    }

    protected function getTokenUrl()
    {
        $baseUrl = config('fortnox.oauth_base_url', 'https://apps.fortnox.se/oauth-v1');
        return "$baseUrl/token";
    }

    protected function getUserByToken($token)
    {
        return [];
    }

    public function token(): ?Token
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException();
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        return new Token(
            Arr::get($response, 'access_token'),
            Arr::get($response, 'refresh_token'),
            Arr::get($response, 'expires_in'),
            explode(' ', Arr::get($response, 'scope', '')),
        );
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        if ($this->hasInvalidState()) {
            throw new InvalidStateException();
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $this->user = $this->mapUserToObject($this->getUserByToken(
            $token = Arr::get($response, 'access_token')
        ));

        $scope = Arr::get($response, 'scope', '');
        $scopes = explode(',', $scope);

        return $this->user->setToken($token)
            ->setRefreshToken(Arr::get($response, 'refresh_token'))
            ->setExpiresIn(Arr::get($response, 'expires_in'))
            ->setApprovedScopes($scopes);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user);
    }

    protected function getTokenHeaders($code)
    {
        return [
            'Content-type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'Authorization' => $this->getAuthorizationHeader(),
        ];
    }

    protected function getAuthorizationHeader(): string
    {
        return 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret);
    }

    protected function getRefreshTokenResponse($refreshToken)
    {
        return json_decode($this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::HEADERS => $this->getTokenHeaders(null),
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ])->getBody(), true);
    }
}
