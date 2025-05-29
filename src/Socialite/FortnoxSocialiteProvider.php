<?php

namespace BernskioldMedia\Fortnox\Socialite;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class FortnoxSocialiteProvider extends AbstractProvider implements ProviderInterface
{

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
}
