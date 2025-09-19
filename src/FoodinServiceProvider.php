<?php

namespace Shandialamp\Foodin;

use Illuminate\Support\ServiceProvider;
use Shandialamp\Foodin\Console\Commands\InstallCommand;

class FoodinServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => $this->app->databasePath('migrations/foodin')
            ], 'migrations');
            $this->publishes([
                __DIR__.'/../database/seeders/' =>  $this->app->databasePath('seeders/foodin'),
            ], 'seeders');
            $this->commands([
                InstallCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/foodin.php' => $this->app->configPath('foodin.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views/foodin' => resource_path('views/vendor/foodin'),
            ], 'foodin-views');
        }
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            $this->app->basePath('vendor/tymon/jwt-auth/config/config.php'),
            'foodin.jwt'
        );
    }
}
