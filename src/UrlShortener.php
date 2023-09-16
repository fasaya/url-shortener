<?php

namespace Fasaya\UrlShortener;

use Illuminate\Support\Str;
use Fasaya\UrlShortener\Model\Link;

class UrlShortener
{

    private static $generate_tries_limit = 5; // how many times generating url before throwing an exception

    public static function add(String $long_url, User $user = null): Link
    {
        self::validUrl($long_url);

        $generate = self::generateShortUrl($long_url);

        return Link::create([
            'slug' => $generate['slug'],
            'long_url' => $long_url,
            'short_url' => $generate['url'],
            'expired_at' => self::getExpireDate(),
        ]);
    }

    public static function addCustom(String $long_url, String $customSlug): Link
    {
        self::validUrl($long_url);

        $short_url = self::generateCustomUrl($customSlug);

        return Link::create([
            'slug' => $customSlug,
            'long_url' => $long_url,
            'short_url' => $short_url,
            'expired_at' => self::getExpireDate(),
        ]);
    }

    public static function exists($short_url): bool
    {
        return Link::where('short_url', $short_url)
            ->where('is_disabled', 0)
            ->orWhere(function ($query) {
                $query->where('expired_at', '>', now());
                $query->whereNull('expired_at');
            })
            ->exists();
    }

    public static function validUrl($url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception('Not a valid URL.');
        }

        return true;
    }

    public static function generateShortUrl($long_url): array
    {
        $slug = Str::random(10);
        $short_url = config('app.url') . config('url-shortener.url-prefix') . '/' . $slug;

        if (self::exists($short_url)) {

            // check if tries limit is reached
            if (self::$generate_tries_limit <= 0) {
                throw new \Exception('Tries limit reached.');
            }

            self::$generate_tries_limit--;

            return self::generateShortUrl($long_url);
        }

        return [
            'slug' => $slug,
            'url' => $short_url
        ];
    }

    public static function generateCustomUrl($slug): string
    {
        $short_url = config('app.url') . config('url-shortener.url-prefix') . '/' . $slug;

        if (self::exists($short_url)) {
            throw new \Exception('URL already exists.');
        }

        return $short_url;
    }

    private static function getExpireDate(): null | string
    {
        return config('url-shortener.expire-days')
            ? date('Y-m-d H:i:s', strtotime('+' . config('url-shortener.expire-days') . ' days', strtotime(now())))
            : NULL;
    }
}
