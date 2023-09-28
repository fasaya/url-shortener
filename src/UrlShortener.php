<?php

namespace Fasaya\UrlShortener;

use Fasaya\UrlShortener\Exceptions\TriesLimitReachedException;
use Fasaya\UrlShortener\Exceptions\UrlExistsException;
use Fasaya\UrlShortener\Exceptions\UrlNotValidException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Fasaya\UrlShortener\Model\Link;
use Fasaya\UrlShortener\Model\LinkClick;

class UrlShortener
{

    private static $generate_tries_limit = 5; // how many times generating url before throwing an exception

    public static function make(String $long_url, $expireDate = NULL): Link
    {
        self::validUrl($long_url);

        $generate = self::generateShortUrl($long_url);

        return Link::create([
            'slug' => $generate['slug'],
            'long_url' => $long_url,
            'short_url' => $generate['url'],
            'expired_at' => $expireDate,
        ]);
    }

    public static function makeCustom(String $long_url, String $customSlug, $expireDate = NULL): Link
    {
        self::validUrl($long_url);

        $short_url = self::generateCustomUrl($customSlug);

        return Link::create([
            'slug' => $customSlug,
            'long_url' => $long_url,
            'short_url' => $short_url,
            'expired_at' => $expireDate,
        ]);
    }

    public static function exists($short_url): bool
    {
        return self::getQuery($short_url)->exists();
    }

    public static function getQuery($short_url)
    {
        return Link::where('is_disabled', 0)
            ->where(function ($query) use ($short_url) {
                $query->where('short_url', $short_url);
                $query->orWhere('slug', $short_url);
            })
            ->where(function ($query) {
                $query->where('expired_at', '>', now());
                $query->orWhereNull('expired_at');
            });
    }

    public static function validUrl($url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new UrlNotValidException();
        }

        return true;
    }

    public static function generateShortUrl($long_url): array
    {
        $slug = Str::random(rand(config('url-shortener.min-length', 6), config('url-shortener.max-length', 10)));
        $short_url = config('app.url') . config('url-shortener.uri', '/l') . '/' . $slug;

        if (self::exists($short_url)) {

            // check if tries limit is reached
            if (self::$generate_tries_limit <= 0) {
                throw new TriesLimitReachedException();
            }

            self::$generate_tries_limit--;

            return self::generateShortUrl($long_url);
        }

        self::validUrl($short_url);

        return [
            'slug' => $slug,
            'url' => $short_url
        ];
    }

    public static function generateCustomUrl($slug): string
    {
        $slug = Str::slug($slug);
        $short_url = config('app.url') . config('url-shortener.uri', '/l') . '/' . $slug;

        if (self::exists($short_url)) {
            throw new UrlExistsException();
        }

        self::validUrl($short_url);

        return $short_url;
    }

    public static function redirect(Request $request, $short_url)
    {
        $link = self::getQuery($short_url);

        if (!$link->exists()) {
            return abort(404);
        }

        $link = $link->first();

        // Record link click
        $referer = $request->server('HTTP_REFERER');
        $linkClick = LinkClick::create([
            'short_link_id' => $link->id,
            'ip' => $request->ip(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'referer' => $referer,
            'referer_host' => parse_url($referer, PHP_URL_HOST),
        ]);

        // Increment click count
        $link->clicks = intval($link->clicks) + 1;
        $link->save();

        // Redirect to full url
        return redirect()->away($link->long_url, 301);
    }
}
