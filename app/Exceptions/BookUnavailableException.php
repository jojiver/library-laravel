<?php

namespace App\Exceptions;

use Exception;

class BookUnavailableException extends Exception
{
    public static function create(): self
    {
        return new self('This book is currently unavailable for borrowing.');
    }
}