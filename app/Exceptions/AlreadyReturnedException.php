<?php

namespace App\Exceptions;

use Exception;

class AlreadyReturnedException extends Exception
{
    public static function create(): self
    {
        return new self('This borrowing has already been returned.');
    }
}