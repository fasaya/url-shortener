<?php

namespace Fasaya\UrlShortener\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'short_links';

    protected $fillable = [
        'slug',
        'short_url',
        'long_url',
        'description',
        'clicks',
        'is_disabled',
        'expired_at',
        'created_by',
        'creator_ip',
        'deleted_by',
        'deleter_ip'
    ];

    public function urlClicks()
    {
        return $this->hasMany(LinkClick::class);
    }

    // public static function add(String $long_url, User $user = null): self
    // {
    //     self::validUrl($long_url);

    //     $generate = self::generateShortUrl($long_url);

    //     return static::create([
    //         'slug' => $generate['slug'],
    //         'long_url' => $long_url,
    //         'short_url' => $generate['url'],
    //         'description' => null,
    //         'expired_at' => config('url-shortener.expire-days') ? date('Y-m-d H:i:s', strtotime('+' . config('url-shortener.expire-days') . ' days', strtotime(now()))) : null,
    //         'created_by' => $user ? $user->id : null,
    //         'creator_ip' => request()->ip(),
    //     ]);
    // }


    // public static function addCustom(String $long_url, String $customSlug, User $user = null): self
    // {
    //     self::validUrl($long_url);

    //     $short_url = self::generateCustomUrl($customSlug);

    //     return static::create([
    //         'slug' => $customSlug,
    //         'long_url' => $long_url,
    //         'short_url' => $short_url,
    //         'description' => null,
    //         'expired_at' => config('url-shortener.expire-days') ? date('Y-m-d H:i:s', strtotime('+' . config('url-shortener.expire-days') . ' days', strtotime(now()))) : null,
    //         'created_by' => $user ? $user->id : null,
    //         'creator_ip' => request()->ip(),
    //     ]);
    // }

    // public static function exists($short_url): bool
    // {
    //     return static::where('short_url', $short_url)
    //         ->where('is_disabled', 0)
    //         ->where('expired_at', '>', now())
    //         ->exists();
    // }

    // public static function validUrl($url): bool
    // {
    //     if (filter_var($url, FILTER_VALIDATE_URL) === false) {
    //         throw new \Exception('Not a valid URL.');
    //     }

    //     return true;
    // }

    // public static function generateShortUrl($long_url): array
    // {
    //     $slug = Str::random(10);
    //     $short_url = config('app.url') . config('url-shortener.url-prefix') . '/' . $slug;

    //     if (self::exists($short_url)) {

    //         // check if tries limit is reached
    //         if (self::$generate_tries_limit <= 0) {
    //             throw new \Exception('Tries limit reached.');
    //         }

    //         self::$generate_tries_limit--;

    //         return static::generateShortUrl($long_url);
    //     }

    //     return [
    //         'slug' => $slug,
    //         'url' => $short_url
    //     ];
    // }

    // public static function generateCustomUrl($slug): string
    // {
    //     $short_url = config('app.url') . config('url-shortener.url-prefix') . '/' . $slug;

    //     if (self::exists($short_url)) {
    //         throw new \Exception('URL already exists.');
    //     }

    //     return $short_url;
    // }
}
