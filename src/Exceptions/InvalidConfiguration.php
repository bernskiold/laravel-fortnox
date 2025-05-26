<?php

namespace BernskioldMedia\Fortnox\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function missingAccessToken(): self
    {
        return new static('Missing access token. To use the Fortnox API you need a valid access token.');
    }

    public static function missingClientSecret(): self
    {
        return new static('Missing client secret. To use the Fortnox API you need to set a valid client secret.');
    }

    public static function missingBaseUrl(): self
    {
        return new static('Missing Fornox Base URL. To use the Fortnox API you need a non-empty base URL set.');
    }

    public static function missingClientId(): self
    {
        return new static('Missing client ID. To use the Fortnox OAuth2 API you need to set a valid client ID.');
    }

    public static function missingRedirectUri(): self
    {
        return new static('Missing redirect URI. To use the Fortnox OAuth2 API you need to set a valid redirect URI.');
    }

    public static function missingAuthUrl(): self
    {
        return new static('Missing authorization URL. To use the Fortnox OAuth2 API you need to set a valid authorization URL.');
    }

    public static function missingTokenUrl(): self
    {
        return new static('Missing token URL. To use the Fortnox OAuth2 API you need to set a valid token URL.');
    }

    public static function missingTenantId(): self
    {
        return new static('Missing tenant ID. When using OAuth2 authentication, you must provide a tenant ID.');
    }
}
