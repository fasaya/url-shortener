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
        // $this->withFactories(__DIR__ . '/factories');
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
        // bug: not migrated by order
        // if add number (sort) on the front of the migration name it will work, but have to change it on service provider too
        $this->turnStubsToMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
        // $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->beforeApplicationDestroyed(function () {
            $this->turnMigrationsToStubs();
            artisan($this, 'migrate:rollback', ['--database' => 'testbench']);
        });


        // $this->turnStubsToMigrations();
        // // Define the order in which migration stubs should be executed
        // $migrationStubsInOrder = [
        //     'create_short_link_table',
        //     'create_short_link_click_table',
        // ];

        // // Generate and execute migrations from stubs in the specified order
        // foreach ($migrationStubsInOrder as $stub) {
        //     // Generate migration file from stub
        //     try {
        //         $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/' . $stub . '.php');
        //     } catch (\Throwable $th) {
        //         //throw $th;
        //     }
        //     // $this->artisan('migrate', [
        //     //     '--database' => 'testbench',
        //     //     '--path' => __DIR__ . '/../database/migrations/' . $stub . '.php',
        //     //     // '--path' => database_path('migrations/' . $stub . '.php'),
        //     // ])->run();
        // }
        // // $this->artisan('migrate', ['--database' => 'testbench'])->run();
        // // $this->loadMigrationsFrom(__DIR__ . '/migrations/');
        // // $this->artisan('migrate', ['--path' => 'tests/migrations', '--database' => 'testbench']);

        // // Rollback migrations in reverse order when the application is destroyed
        // $this->beforeApplicationDestroyed(function () use ($migrationStubsInOrder) {
        //     $this->artisan('migrate:rollback', ['--database' => 'testbench']);
        //     $this->turnMigrationsToStubs();
        // });
    }

    protected function turnStubsToMigrations(): void
    {
        $migrationsFolder = __DIR__ . '/../database/migrations/';
        $migrations = scandir($migrationsFolder);
        foreach ($migrations as $migration) {
            if ($migration === '.' || $migration === '..') continue;

            $newName = str_replace('.php.stub', '.php', $migration);

            rename($migrationsFolder . $migration, $migrationsFolder . $newName);
        }
    }

    protected function turnMigrationsToStubs(): void
    {
        $migrationsFolder = __DIR__ . '/../database/migrations/';
        $migrations = scandir($migrationsFolder);
        foreach ($migrations as $migration) {
            if ($migration === '.' || $migration === '..') continue;

            $newName = str_replace('.php', '.php.stub', $migration);

            rename($migrationsFolder . $migration, $migrationsFolder . $newName);
        }
    }
}
