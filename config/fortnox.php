<?php

return [
    /**
     * The client ID provided by Fortnox for your application.
     * Required for OAuth2 authentication.
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
     * The URL to the Fortnox OAuth2 authorization server.
     */
    'auth_url' => env('FORTNOX_AUTH_URL', 'https://apps.fortnox.se/oauth-v1/auth'),

    /**
     * The URL to the Fortnox OAuth2 token endpoint.
     */
    'token_url' => env('FORTNOX_TOKEN_URL', 'https://apps.fortnox.se/oauth-v1/token'),

    /**
     * The redirect URI for the OAuth2 flow.
     * This must match the redirect URI registered in the Fortnox developer portal.
     */
    'redirect_uri' => env('FORTNOX_REDIRECT_URI', ''),

    /**
     * The scopes to request during the OAuth2 flow.
     * Available scopes: https://www.fortnox.se/developer/authorization
     */
    'scopes' => env('FORTNOX_SCOPES', ''),

    /**
     * The state parameter for the OAuth2 flow.
     * This is used to prevent CSRF attacks.
     */
    'state' => env('FORTNOX_STATE', ''),

    /**
     * The default tenant ID to use for OAuth2 authentication.
     * This can be overridden at runtime using the forTenant() method.
     */
    'tenant_id' => env('FORTNOX_TENANT_ID', 'default'),

    /**
     * The storage driver to use for storing OAuth2 tokens.
     * Options: 'database', 'cache'
     */
    'token_storage' => env('FORTNOX_TOKEN_STORAGE', 'database'),

    /**
     * The database table to use for storing OAuth2 tokens.
     * Only used when token_storage is set to 'database'.
     */
    'token_table' => env('FORTNOX_TOKEN_TABLE', 'fortnox_tokens'),

    /**
     * The cache key prefix to use for storing OAuth2 tokens.
     * Only used when token_storage is set to 'cache'.
     */
    'token_cache_prefix' => env('FORTNOX_TOKEN_CACHE_PREFIX', 'fortnox_token_'),
];
