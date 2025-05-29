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

    public static function invalidStorageConfiguration(string $message): self
    {
        return new static("Invalid Storage Configuration: {$message}");
    }

}
