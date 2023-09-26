<?php

namespace Fasaya\UrlShortener;

use Illuminate\Support\Arr;
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
                'create_short_link_table',
                'create_short_link_click_table'
            ])
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->installRoutes();
    }

    protected function installRoutes(): self
    {
        $config = $this->app['config']->get('url-shortener.route', []);
        $config['namespace'] = 'fasaya\UrlShortener';

        Route::group($config, function () {
            Route::get('/{slug}', [UrlShortenerController::class, 'index'])->name('url-shortener.index');
        });

        // Install the Admin routes
        $config_admin = $this->app['config']->get('url-shortener.admin-route', []);
        $config_admin['namespace'] = 'fasaya\UrlShortener';

        if (Arr::get($config_admin, 'enabled', true)) {
            Route::group($config_admin, function () {
                Route::get('/', [AdminController::class, 'index'])->name('index');
                Route::post('/', [AdminController::class, 'store'])->name('store');
                Route::get('/create', [AdminController::class, 'create'])->name('create');
                Route::get('/{link}', [AdminController::class, 'show'])->name('show');
                Route::put('/{link}', [AdminController::class, 'update'])->name('update');
                Route::delete('/{link}', [AdminController::class, 'destroy'])->name('destroy');
                Route::get('{link}/edit', [AdminController::class, 'edit'])->name('edit');
            });
        }

        return $this;
    }
}
