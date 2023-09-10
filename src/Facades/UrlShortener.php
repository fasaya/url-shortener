<?php

namespace Fasaya\UrlShortener\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fasaya\UrlShortener\UrlShortener
 */
class UrlShortener extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Fasaya\UrlShortener\UrlShortener::class;
    }
}
