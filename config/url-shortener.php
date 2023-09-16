<?php

// config for Fasaya/UrlShortener
return [

    /**
     * Here you may specify the fully qualified class name of the user model class.
     */
    'user-model' => App\Models\User::class,

    /**
     * Optionally set url access duration (days), set to null to keep forever.
     * Example
     * 'expire-days' => 30
     */
    'expire-days' => null,

    /**
     * URL route for the shortened URL
     * Example
     *  'uri' => '/l', // https://yourdomain.com/l/exampleString
     */
    'uri' => '/l',

    /**
     * Where should the URL shortener route be?
     */
    'route' => [
        'prefix' => 'url-shortener',
        'middleware' => [
            // 'web',
        ],
    ],

    /**
     * Where should the admin route be?
     */
    'admin-route' => [
        'enabled' => true, // Should the admin routes be enabled?
        'prefix' => 'url-shortener-manager',
        'middleware' => [
            'web',
        ],
    ],

    /**
     * Admin Template
     * example
     * 'name' => 'layouts.app' for Default urlShortener use 'urlShortenerViews::layouts.app'
     * 'section' => 'content' for Default urlShortener use 'content'
     * 'styles_section' => 'styles' for Default urlShortener use 'styles'
     */
    'admin-template' => [
        'name' => 'urlShortenerViews::layouts.app',
        'section' => 'content',
    ],

    /**
     * Number of emails per page in the admin view
     */
    'links-per-page' => 30,

    /**
     * Date Format
     */
    'date-format' => 'm/d/Y g:i a',

    /**
     * Maximum character length of shortened url slug
     */
    'min-length' => 6,

    /**
     * Maximum character length of shortened url slug
     */
    'max-length' => 10,

];
