<?php

namespace BernskioldMedia\Fortnox\TokenStorage;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use BernskioldMedia\Fortnox\Exceptions\InvalidConfiguration;
use Illuminate\Support\Facades\Cache;

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
     * Store the token data for a specific tenant.
     */
    public function storeToken(string $token): void
    {
        $settings = app($this->settingsClass);
        $settings->{$this->settingName} = $token;
        $settings->save();
    }

    /**
     * Get the token data for a specific tenant.
     */
    public function getToken(): ?string
    {
        $settings = app($this->settingsClass);

        return $settings->{$this->settingName} ?? null;
    }

    /**
     * Delete the token data for a specific tenant.
     */
    public function deleteToken(): void
    {
        $settings = app($this->settingsClass);
        $settings->{$this->settingName} = null;
        $settings->save();
    }

    /**
     * Check if a token exists for a specific tenant.
     */
    public function hasToken(): bool
    {
        $settings = app($this->settingsClass);

        return !empty($settings->{$this->settingName});
    }
}
