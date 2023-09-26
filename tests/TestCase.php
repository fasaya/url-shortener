<?php

namespace Fasaya\UrlShortener\Tests;

use function Orchestra\Testbench\artisan;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;
use Fasaya\UrlShortener\UrlShortenerServiceProvider;

class TestCase extends Orchestra
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'testing_url_shortener',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]);
        $app['config']->set('app.debug', true);

        $app['router']->getRoutes()->refreshNameLookups();
    }

    protected function getPackageProviders($app)
    {
        return [
            UrlShortenerServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        // RefreshDatabase trait isn't working so had to do it manually
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        $migrationStubsInOrder = [
            'create_short_link_table',
            'create_short_link_click_table',
        ];

        foreach ($migrationStubsInOrder as $migration) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/' . $migration . '.php');
        }

        // $this->artisan('migrate', ['--database' => 'testbench'])->run();

        // $this->beforeApplicationDestroyed(function () {
        //     artisan($this, 'migrate:rollback', ['--database' => 'testbench']);
        // });
    }
}
