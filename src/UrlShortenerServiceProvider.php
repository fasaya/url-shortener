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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_url-shortener_table')
            ->hasCommand(UrlShortenerCommand::class);
    }
}
