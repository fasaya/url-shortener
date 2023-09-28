<?php

// config for Fasaya/UrlShortener
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
