<?php

namespace Wv\ModuleInstallerKit\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;
use Wv\ModuleInstallerKit\ModuleRegistry;
use Wv\ModuleInstallerKit\Support\ComposerConfigPatcher;
use Wv\ModuleInstallerKit\Support\ManifestWriter;
use Wv\ModuleInstallerKit\Support\NodeDependencyMerger;
use Wv\ModuleInstallerKit\Support\RepositoryFetcher;
use Wv\ModuleInstallerKit\Support\StubCopier;
use Wv\ModuleInstallerKit\Support\ViteConfigPatcher;

class InstallCommand extends Command
{
    protected $signature = 'wv:install
        {modules?* : Module keys to install, e.g. "core". Omit and pass --all to install everything}
        {--all : Install every module this package knows about}
        {--force : Overwrite the module directory if it already exists}';

    protected $description = "Fetch one or more Wv modules from their GitHub repos and copy them into this app's Modules/ directory";

    public function handle(
        ModuleRegistry $registry,
        RepositoryFetcher $fetcher,
        StubCopier $copier,
        ManifestWriter $manifest,
        NodeDependencyMerger $nodeDeps,
        ViteConfigPatcher $vite,
        ComposerConfigPatcher $composerPatcher,
        Filesystem $files,
    ): int {
        $keys = $this->option('all') ? $registry->all()->keys()->all() : $this->argument('modules');

        if ($keys === []) {
            $this->error('Specify one or more modules, or pass --all. Run `php artisan wv:list` to see what\'s available.');

            return self::FAILURE;
        }

        $modules = $registry->resolveWithDependencies($keys);

        $composerPatcher->ensureModuleMergePluginConfigured(base_path('composer.json'));

        $packageJsonChanged = false;
        $installed = [];
        $skipped = [];

        foreach ($modules as $module) {
            $target = base_path($module['target']);

            if ($files->isDirectory($target) && ! $this->option('force')) {
                $skipped[] = $module['name'];
                $this->warn("{$module['name']} is already installed at {$module['target']} — skipping. Use --force to overwrite, or `wv:update {$module['key']}` to sync.");

                continue;
            }

            $this->info("Fetching {$module['name']} from {$module['repo']}@{$module['ref']}...");
            $fetched = $fetcher->fetch($module['repo'], $module['ref']);

            try {
                $copier->copy($fetched['path'].'/source', $target);
                $manifest->write($target, $module['key'], $fetched['commit']);

                $npmDeps = $module['npm'] ? $fetched['path'].'/'.$module['npm'] : null;

                if ($npmDeps && $nodeDeps->merge(base_path('package.json'), $npmDeps)) {
                    $packageJsonChanged = true;
                }

                $vite->patch(base_path('vite.config.js'), [
                    "Modules/{$module['name']}/resources/css/app.css",
                    "Modules/{$module['name']}/resources/js/app.js",
                ]);
            } finally {
                $files->deleteDirectory($fetched['path']);
            }

            $this->markModuleEnabled($module['name']);
            $installed[] = $module['name'];
        }

        if ($installed !== []) {
            Process::run('composer dump-autoload');
        }

        if ($packageJsonChanged) {
            $this->info('New npm dependencies were added — running npm install...');
            Process::forever()->run('npm install');
        }

        $this->newLine();
        $this->info('Installed: '.($installed === [] ? '(none)' : implode(', ', $installed)));

        if ($skipped !== []) {
            $this->comment('Skipped (already present): '.implode(', ', $skipped));
        }

        return self::SUCCESS;
    }

    protected function markModuleEnabled(string $name): void
    {
        $statusesPath = base_path('modules_statuses.json');
        $statuses = file_exists($statusesPath)
            ? (json_decode(file_get_contents($statusesPath), true) ?? [])
            : [];
        $statuses[$name] = true;
        file_put_contents($statusesPath, json_encode($statuses, JSON_PRETTY_PRINT).PHP_EOL);
    }
}
