<?php

namespace Wv\ModuleInstallerKit\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;
use Wv\ModuleInstallerKit\ModuleRegistry;
use Wv\ModuleInstallerKit\Support\ManifestWriter;
use Wv\ModuleInstallerKit\Support\NodeDependencyMerger;
use Wv\ModuleInstallerKit\Support\StubCopier;
use Wv\ModuleInstallerKit\Support\ViteConfigPatcher;

class UpdateCommand extends Command
{
    protected $signature = 'wv:update
        {modules?* : Module keys to update, e.g. "core". Omit and pass --all to update everything installed}
        {--all : Update every module this package ships that is currently installed}
        {--force : Skip the confirmation prompt}';

    protected $description = 'Overwrite installed Wv modules with the version currently shipped by this package';

    public function handle(
        ModuleRegistry $registry,
        StubCopier $copier,
        ManifestWriter $manifest,
        NodeDependencyMerger $nodeDeps,
        ViteConfigPatcher $vite,
        Filesystem $files,
    ): int {
        $keys = $this->option('all') ? $registry->all()->keys()->all() : $this->argument('modules');

        if ($keys === []) {
            $this->error('Specify one or more modules, or pass --all.');

            return self::FAILURE;
        }

        $modules = $registry->resolveWithDependencies($keys);

        $toUpdate = array_filter($modules, fn (array $module) => $files->isDirectory(base_path($module['target'])));
        $notInstalled = array_filter($modules, fn (array $module) => ! $files->isDirectory(base_path($module['target'])));

        foreach ($notInstalled as $module) {
            $this->warn("{$module['name']} isn't installed — skipping. Run `wv:install {$module['key']}` first.");
        }

        if ($toUpdate === []) {
            return self::SUCCESS;
        }

        // Updates always fully overwrite the module directory — Core (and
        // every module this package ships) is meant to be replaced wholesale
        // on update, not diffed against local edits. Customization has a
        // separate, safe mechanism outside the module directory itself; this
        // command intentionally does not try to preserve in-place edits.
        if (! $this->option('force')) {
            $this->warn('This overwrites the module directory completely, including any local edits: '.implode(', ', array_column($toUpdate, 'name')));

            if (! $this->confirm('Continue?')) {
                return self::SUCCESS;
            }
        }

        $packageJsonChanged = false;
        $updated = [];

        foreach ($toUpdate as $module) {
            $target = base_path($module['target']);

            $copier->copy($module['source'], $target);
            $manifest->write($target, $module['key'], $module['version']);

            if ($module['npm'] && $nodeDeps->merge(base_path('package.json'), $module['npm'])) {
                $packageJsonChanged = true;
            }

            $vite->patch(base_path('vite.config.js'), [
                "Modules/{$module['name']}/resources/css/app.css",
                "Modules/{$module['name']}/resources/js/app.js",
            ]);

            $updated[] = $module['name'];
        }

        Process::run('composer dump-autoload');

        if ($packageJsonChanged) {
            $this->info('New npm dependencies were added — running npm install...');
            Process::forever()->run('npm install');
        }

        $this->info('Updated: '.implode(', ', $updated));

        return self::SUCCESS;
    }
}
