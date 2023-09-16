<?php

namespace Fasaya\UrlShortener;

use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ]);
    }

    public function packageBooted(): void
    {
        $this->installRoutes();
    }

    protected function installRoutes(): self
    {
        Route::get(config('url-shortener.url-prefix') . '/{slug}', [UrlShortenerController::class, 'index'])->name('url-shortener.index');

        if (config('url-shortener.admin-route.enabled')) {
            // Route::resource(config('url-shortener.admin-route.url-prefix'), AdminController::class);
        }

        return $this;
    }
}
