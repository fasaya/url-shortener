# Laravel Url Shortener

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fasaya/url-shortener.svg?style=flat-square)](https://packagist.org/packages/fasaya/url-shortener)
[![Total Downloads](https://img.shields.io/packagist/dt/fasaya/url-shortener.svg?style=flat-square)](https://packagist.org/packages/fasaya/url-shortener)

This is a package to shorten your url and manage your shorten urls

## Installation

You can install the package via composer:

```bash
composer require fasaya/url-shortener
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="url-shortener-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="url-shortener-config"
```

This is the contents of the published config file:

```php
return [

    /**
     * Here you may specify the fully qualified class name of the user model class.
     */
    'user-model' => App\Models\User::class,

    /**
     * Maximum character length of shortened url slug
     */
    'min-length' => 6,

    /**
     * Maximum character length of shortened url slug
     */
    'max-length' => 10,

    /**
     * URL route for the shortened URL
     * Example
     *  'prefix' => '/l', // https://yourdomain.com/l/exampleString
     */
    'route' => [
        'prefix' => '/l',
        'middleware' => [
            // 'web',
        ],
    ],

    /**
     * Where should the admin route be?
     */
    'admin-route' => [
        'enabled' => true, // Should the admin routes be enabled?
        'as' => 'url-shortener-manager.',
        'prefix' => 'url-shortener-manager',
        'middleware' => [
            'web',
        ],
    ],

    /**
     * Admin Template
     * example
     * 'name' => 'layouts.app' for Default urlShortener use 'url-shortener::layouts.app'
     * 'section' => 'content' for Default urlShortener use 'content'
     * 'styles_section' => 'page_style' for Default urlShortener use 'page_style'
     * 'script_section' => 'page_script' for Default urlShortener use 'page_script'
     */
    'admin-template' => [
        'name' => 'url-shortener::layouts.app',
        'section' => 'content',
        'styles_section' => 'page_style',
        'script_section' => 'page_script',
    ],

    /**
     * Number of emails per page in the admin view
     */
    'links-per-page' => 30,

    /**
     * Date Format
     */
    'date-format' => 'm/d/Y g:i a',

];
```

Optionally, you can publish the url shortener manager dashboard views using

```bash
php artisan vendor:publish --tag="url-shortener-views"
```

## Usage

```php
use Fasaya\UrlShortener\UrlShortener;

// Add an expiration date (optional)
$expire_at = '2025-12-12 00:00:00'; // NULL for no expiration date

// Generate a random short URL
$link = UrlShortener::make('http://example.com', $expire_at)->short_url;

// Generate a custom short URL
$link = UrlShortener::make('http://example.com', 'custom-short-slug', $expire_at)->short_url; 
```

## Exceptions

The following exceptions may be thrown. You may add them to your ignore list in your exception handler, or handle them as you wish.

-   Fasaya\UrlShortener\Exceptions\UrlNotValidException - Not a valid URL.
-   Fasaya\UrlShortener\Exceptions\UrlExistsException - URL already exists and active.
-   Fasaya\UrlShortener\Exceptions\TriesLimitReachedException - Generating reached tries limit.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fasaya](https://github.com/fasaya)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
