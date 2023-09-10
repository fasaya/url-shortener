<?php

namespace Fasaya\UrlShortener\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'short_links';

    protected $fillable = [
        'short_url',
        'long_url',
        'clicks',
        'secret_key',
        'is_disabled',
        'created_by',
        'creator_ip',
        'deleted_by',
        'deleter_ip'
    ];

    private $generate_tries_limit = 5; // how many times generating url before throwing an exception

    public function urlClicks()
    {
        return $this->hasMany(LinkClick::class);
    }

    public static function add($long_url, Model $user): self
    {
        self::validUrl($long_url);

        $short_url = self::generateShortUrl($long_url);

        if (!static::hasAllowedValues($value)) {
            throw InvalidMarkValueException::create();
        }

        $attributes = [
            app(static::class)->getUserIdColumn() => $user->getKey(),
            'markable_id' => $markable->getKey(),
            'markable_type' => $markable->getMorphClass(),
            'value' => $value,
        ];

        $values = static::forceSingleValuePerUser()
            ? [Arr::pull($attributes, 'value')]
            : [];

        return static::firstOrCreate($attributes, $values);
    }

    public static function exists($short_url): bool
    {
        return static::where('short_url', $short_url)->where('is_disabled', 0)->exists();
    }

    public static function validUrl($url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            // throw exception
        }

        return true;
    }

    public static function generateShortUrl($long_url, $custom_string = NULL): string
    {
        $short_url = Str::random(10);

        if (self::exists($short_url)) {

            // check if tries limit is reached
            if (self::$generate_tries_limit <= 0) {
                throw new \Exception('Tries limit reached');
            }

            self::$generate_tries_limit--;

            return static::generateShortUrl($long_url);
        }

        return $short_url;
    }

    public static function generateCustomUrl($custom_string): string
    {
        if (self::exists($short_url)) {
            throw new \Exception('Tries limit reached');
        }

        return $short_url;
    }
}
