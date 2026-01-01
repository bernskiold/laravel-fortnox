<?php

namespace BernskioldMedia\Fortnox\Exceptions;

use Exception;

class InvalidParameter extends Exception
{
    public static function tooManyPerPage(): self
    {
        return new static('Too many records per page. The Fortnox API only supports up to 500 records per page.');
    }
}
