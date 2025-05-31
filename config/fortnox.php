<?php

use BernskioldMedia\Fortnox\TokenStorage\CacheTokenStorage;

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
     * Service accounts are used when the actions performed by your application
     * should not be tied to a specific user, but rather to the application itself.
     *
     * If you want each user to authenticate with Fortnox, set this to false.
     */
    'use_service_account' => true,

    /**
     * The scopes to request from Fortnox.
     *
     * You can specify the scopes your application needs to access.
     * @see https://www.fortnox.se/developer/guides-and-good-to-know/scopes
     */
    'scopes' => env('FORTNOX_SCOPES', ''),

    'routes' => [

        /**
         * Middlewares for the Fortnox Oauth routes.
         */
        'middleware' => ['web'],

        'oauth' => [
            /**
             * The route to redirect to when the user wants to authenticate with Fortnox.
             * This should point to the controller that handles the OAuth flow.
             */
            'redirect' => '/oauth/fortnox',

            /**
             * The route to redirect to after the user has authenticated with Fortnox.
             * This should point to the controller that handles the OAuth callback.
             */
            'callback' => '/oauth/fortnox/callback',
        ],
    ],

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
