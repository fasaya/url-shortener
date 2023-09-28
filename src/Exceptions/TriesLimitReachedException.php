<?php

namespace Fasaya\UrlShortener\Exceptions;

use Exception;
use Throwable;

class TriesLimitReachedException extends Exception
{
    public function __construct($message = 'Tries limit reached.', $code = 409, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
