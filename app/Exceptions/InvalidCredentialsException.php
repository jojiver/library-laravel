<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public static function create(): self
    {
        return new self('Invalid credentials.');
    }
}