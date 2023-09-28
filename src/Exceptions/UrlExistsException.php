<?php

namespace Fasaya\UrlShortener\Exceptions;

use Exception;
use Throwable;

class UrlExistsException extends Exception
{
    public function __construct($message = 'URL already exists.', $code = 409, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
