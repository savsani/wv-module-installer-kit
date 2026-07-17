<?php

namespace Modules\Core\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Nwidart\Modules\Support\ModuleServiceProvider;

class CoreServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Core';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'core';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Register the service providers.
     */
    public function register(): void
    {
        parent::register();

        // Preserves config('ui.*') call sites in ui/badge, ui/button, etc.
        // unchanged — this module's config file isn't named after the
        // module's own config('core') key.
        $this->mergeConfigFrom(module_path($this->name, 'config/ui.php'), 'ui');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // Merges this module's views/components into the same unnamespaced
        // resolution path the host app already uses, so @extends('layouts.app'),
        // <x-ui.button>, <x-form.input>, view('examples.data-table') etc. keep
        // working unchanged everywhere — including the Admin module's views,
        // which never need to know this module exists.
        View::addLocation(module_path($this->name, 'resources/views'));
        Blade::anonymousComponentPath(module_path($this->name, 'resources/views/components'));
    }

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
