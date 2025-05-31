<?php

namespace BernskioldMedia\Fortnox\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{

    public static function missingClientId(): self
    {
        return new static('Missing client ID. To use the Fortnox API you need to set a valid client ID.');
    }

    public static function missingClientSecret(): self
    {
        return new static('Missing client secret. To use the Fortnox API you need to set a valid client secret.');
    }

    public static function missingBaseUrl(): self
    {
        return new static('Missing Fornox Base URL. To use the Fortnox API you need a non-empty base URL set.');
    }

    public static function missingStorageProvider(): self
    {
        return new static('You need to have a storage provider for the access token configured.');
    }

    public static function invalidStorageConfiguration(string $message): self
    {
        return new static("Invalid Storage Configuration: {$message}");
    }

}
