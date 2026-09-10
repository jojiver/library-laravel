<?php

namespace App\Exceptions;

use Exception;

class BookHasActiveBorrowingsException extends Exception
{
    public static function create(): self
    {
        return new self('This book cannot be deleted while it still has active borrowings.');
    }
}