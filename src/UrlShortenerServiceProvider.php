<?php

namespace Fasaya\UrlShortener;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Fasaya\UrlShortener\Commands\UrlShortenerCommand;

class UrlShortenerServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('url-shortener')
            ->hasConfigFile('url-shortener')
            ->hasMigrations([
                'create_link_table',
                'create_link_click_table'
            ])
            // ->hasRoutes([])
            // ->hasCommand(UrlShortenerCommand::class)
        ;
    }
}
