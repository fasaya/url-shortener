<?php

namespace Fasaya\UrlShortener;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Fasaya\UrlShortener\Model\Link;
use Fasaya\UrlShortener\Model\LinkClick;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UrlShortener
{

    private static $generate_tries_limit = 5; // how many times generating url before throwing an exception

    public static function make(String $long_url): Link
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

    public static function makeCustom(String $long_url, String $customSlug): Link
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
        return Link::where('is_disabled', 0)
            ->where(function ($query) use ($short_url) {
                $query->where('short_url', $short_url);
                $query->orWhere('slug', $short_url);
            })
            ->where(function ($query) {
                $query->where('expired_at', '>', now());
                $query->orWhereNull('expired_at');
            })
            ->exists();
    }

    public static function validUrl($url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new HttpException(400, 'Not a valid URL.');
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
                throw new HttpException(500, 'Tries limit reached.');
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
        $short_url = config('app.url') . config('url-shortener.url-prefix') . '/' . $slug;

        if (self::exists($short_url)) {
            throw new HttpException(500, 'URL already exists.');
        }

        self::validUrl($short_url);

        return $short_url;
    }

    private static function getExpireDate(): null | string
    {
        return config('url-shortener.expire-days')
            ? date('Y-m-d H:i:s', strtotime('+' . config('url-shortener.expire-days') . ' days', strtotime(now())))
            : NULL;
    }

    public static function redirect(Request $request, $short_url)
    {
        if (!self::exists($short_url)) {
            return abort(404);
        }

        $link = Link::where('short_url', $short_url)->orWhere('slug', $short_url)->first();

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
        return redirect()->to($link->long_url, 301);
    }
}
