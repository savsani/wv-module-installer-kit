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
        $this->app->singleton(ModuleRegistry::class, fn () => new ModuleRegistry(__DIR__.'/../stubs'));
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                UpdateCommand::class,
                ListCommand::class,
            ]);
        }
    }
}
