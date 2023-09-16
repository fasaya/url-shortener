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
        $config = $this->app['config']->get('url-shortener.route', []);
        $config['namespace'] = 'fasaya\UrlShortener';

        Route::group($config, function () {
            Route::get(config('url-shortener.uri', '/l') . '/{slug}', [UrlShortenerController::class, 'index'])->name('url-shortener.index');
        });

        // if (config('url-shortener.admin-route.enabled')) {
        //     Route::resource(config('url-shortener.admin-route.uri'), AdminController::class);
        // }

        return $this;
    }

    /**
     * Publish the views
     *
     * @return void
     */
    protected function publishViews()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'urlShortenerViews');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/vendor/urlShortenerViews'),
        ]);
    }
}
