<?php

namespace Aliziodev\Wilayah;

use Aliziodev\Wilayah\Commands\WilayahCacheClearCommand;
use Aliziodev\Wilayah\Commands\WilayahInstallCommand;
use Aliziodev\Wilayah\Commands\WilayahSeedCommand;
use Aliziodev\Wilayah\Commands\WilayahSyncCommand;
use Aliziodev\Wilayah\Commands\WilayahVersionCommand;
use Aliziodev\Wilayah\Services\CacheService;
use Aliziodev\Wilayah\Services\DropdownService;
use Aliziodev\Wilayah\Services\HierarchyService;
use Aliziodev\Wilayah\Services\SearchByPostalCodeService;
use Aliziodev\Wilayah\Services\SearchService;
use Illuminate\Support\ServiceProvider;

class WilayahServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/wilayah.php',
            'wilayah'
        );

        $this->app->singleton(CacheService::class, fn ($app) => new CacheService(
            $app['cache'],
            config('wilayah.cache')
        ));

        $this->app->singleton(SearchService::class, fn ($app) => new SearchService(
            $app->make(CacheService::class)
        ));

        $this->app->singleton(SearchByPostalCodeService::class, fn ($app) => new SearchByPostalCodeService(
            $app->make(CacheService::class)
        ));

        $this->app->singleton(HierarchyService::class, fn ($app) => new HierarchyService(
            $app->make(CacheService::class)
        ));

        $this->app->singleton(DropdownService::class, fn ($app) => new DropdownService(
            $app->make(CacheService::class)
        ));

        $this->app->singleton('wilayah', fn ($app) => new WilayahManager(
            $app->make(SearchService::class),
            $app->make(SearchByPostalCodeService::class),
            $app->make(HierarchyService::class),
            $app->make(DropdownService::class),
            $app->make(CacheService::class),
        ));
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->publishMigrations();
            $this->registerCommands();
        }
    }

    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config/wilayah.php' => config_path('wilayah.php'),
        ], 'wilayah-config');
    }

    protected function publishMigrations(): void
    {
        $this->publishes([
            __DIR__.'/Database/Migrations/' => database_path('migrations'),
        ], 'wilayah-migrations');
    }

    protected function registerCommands(): void
    {
        $this->commands([
            WilayahInstallCommand::class,
            WilayahSeedCommand::class,
            WilayahSyncCommand::class,
            WilayahVersionCommand::class,
            WilayahCacheClearCommand::class,
        ]);
    }
}
