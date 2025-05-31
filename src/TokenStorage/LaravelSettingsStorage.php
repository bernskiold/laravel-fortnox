<?php

namespace BernskioldMedia\Fortnox\TokenStorage;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use BernskioldMedia\Fortnox\Data\StoredToken;
use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Two\Token;
use function serialize;
use function unserialize;

class LaravelSettingsStorage implements TokenStorage
{

    protected string $settingsClass;

    protected string $settingName;

    public function __construct()
    {
        $settingsClass = config('fortnox.provider_configuration.laravel_settings.settings_class');
        $settingName = config('fortnox.provider_configuration.laravel_settings.setting_name');

        if (!$settingsClass) {
            throw InvalidConfiguration::invalidStorageConfiguration(
                'The settings class is not set in the configuration.'
            );
        }

        if (!$settingName) {
            throw InvalidConfiguration::invalidStorageConfiguration(
                'The setting name is not set in the configuration.'
            );
        }

        $this->settingsClass = $settingsClass;
        $this->settingName = $settingName;
    }

    /**
     * Store the token data.
     */
    public function storeToken(Token $token): void
    {
        $storedToken = StoredToken::fromSocialiteToken($token);

        $settings = app($this->settingsClass);
        $settings->{$this->settingName} = $storedToken->serialize();
        $settings->save();
    }

    /**
     * Get the token data.
     */
    public function getToken(): ?StoredToken
    {
        $settings = app($this->settingsClass);

        if (empty($settings->{$this->settingName})) {
            return null;
        }

        return StoredToken::fromSerialized($settings->{$this->settingName});
    }

    /**
     * Delete the token data.
     */
    public function deleteToken(): void
    {
        $settings = app($this->settingsClass);
        $settings->{$this->settingName} = null;
        $settings->save();
    }

    /**
     * Check if a token exists.
     */
    public function hasToken(): bool
    {
        $settings = app($this->settingsClass);

        return !empty($settings->{$this->settingName});
    }
}
