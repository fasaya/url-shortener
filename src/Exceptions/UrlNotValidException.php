<?php

namespace Fasaya\UrlShortener\Exceptions;

use Exception;
use Throwable;

class UrlNotValidException extends Exception
{
    public function __construct($message = 'Not a valid URL.', $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
