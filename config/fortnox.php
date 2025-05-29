<?php

use BernskioldMedia\Fortnox\Storage\CacheTokenStorage;

return [

    /**
     * The client ID provided by Fortnox for your application.
     */
    'client_id' => env('FORTNOX_CLIENT_ID', ''),

    /**
     * The client secret provided by Fortnox for your application.
     */
    'client_secret' => env('FORTNOX_CLIENT_SECRET', ''),

    /**
     * The URL to the Fortnox API.
     * This package currently only supports version 3.
     */
    'base_url' => env('FORTNOX_BASE_URL', 'https://api.fortnox.se/3/'),

    /**
     * The OAuth base URL for Fortnox. This is used for the OAuth flow.
     */
    'oauth_base_url' => env('FORTNOX_OAUTH_BASE_URL', 'https://apps.fortnox.se/oauth-v1'),

    /**
     * The redirect URL to use after a successful authentication.
     * This should be the URL where you want to redirect the user after they have authenticated.
     */
    'success_redirect_url' => env('FORTNOX_SUCCESS_REDIRECT_URL', '/'),

    /**
     * The redirect URL to use after an error during authentication.
     * This should be the URL where you want to redirect the user if there is an error during authentication.
     *
     * The query string will contain an an `error` parameter with the error message.
     */
    'error_redirect_url' => env('FORTNOX_ERROR_REDIRECT_URL', '/'),

    /**
     * The redirect URL for the OAuth flow.
     * This should match the redirect URL configured in your Fortnox application settings.
     *
     * Example: https://yourapp.com/auth/fortnox/callback
     */
    'oauth_redirect_url' => '',

    'scopes' => [],

    /**
     * The storage provider to use for storing tokens.
     *
     * This should implement the `BernskioldMedia\Fortnox\Contracts\TokenStorage` interface.
     */
    'storage_provider' => CacheTokenStorage::class,

    /**
     * The configuration for the providers.
     *
     * You may add additional keys and configuration objects here for
     * your own custom providers.
     */
    'provider_configuration' => [

        /**
         * The cache settings are only used if the
         * storage provider is set to CacheTokenStorage.
         */
        'cache' => [

            /**
             * The cache driver to use for storing tokens.
             * This should be set to the driver you want to use, such as 'file', 'redis', etc.
             *
             * If set to null, the default cache driver will be used.
             */
            'driver' => null,

            /**
             * The cache prefix to use for storing tokens.
             * This should be unique to avoid conflicts with other cached items.
             */
            'prefix' => 'fortnox.token',

            /**
             * The default expiration time for tokens in seconds.
             *
             * After this time the user will have to re-authenticate.
             */
            'expires_in' => 60 * 60 * 24, // 24 hours
        ],

//        'laravel_settings' => [
//
//            /**
//             * The settings class to use for storing tokens.
//             * This should be a class that extends `Spatie\LaravelSettings\Settings`.
//             */
//            'settings_class' => \App\Settings\ApiSettings::class,
//
//            /**
//             * The name of the setting to use for storing the token.
//             * This should match the property name in your settings class.
//             */
//            'setting_name' => 'fortnox_access_token',
//        ],

    ],

];
