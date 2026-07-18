<?php

namespace Wv\ModuleInstallerKit;

use Illuminate\Support\ServiceProvider;
use Wv\ModuleInstallerKit\Console\InstallCommand;
use Wv\ModuleInstallerKit\Console\ListCommand;
use Wv\ModuleInstallerKit\Console\UpdateCommand;

class ModuleInstallerKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/wv-modules.php', 'wv-modules');

        $this->app->singleton(
            ModuleRegistry::class,
            fn ($app) => new ModuleRegistry($app['config']->get('wv-modules'))
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/wv-modules.php' => config_path('wv-modules.php'),
            ], 'wv-modules-config');

            $this->commands([
                InstallCommand::class,
                UpdateCommand::class,
                ListCommand::class,
            ]);
        }
    }
}
