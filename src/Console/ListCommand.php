<?php

namespace Wv\ModuleInstallerKit\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Wv\ModuleInstallerKit\ModuleRegistry;
use Wv\ModuleInstallerKit\Support\ModuleVersionChecker;

class ListCommand extends Command
{
    protected $signature = 'wv:list';

    protected $description = 'List every module this package knows about, its installed version, and the latest available version';

    public function handle(ModuleRegistry $registry, ModuleVersionChecker $versionChecker, Filesystem $files): int
    {
        $rows = $registry->all()->map(function (array $module) use ($files, $versionChecker) {
            $manifestPath = base_path($module['target']).'/.wv-manifest.json';
            $installedVersion = $files->exists($manifestPath)
                ? json_decode($files->get($manifestPath), true)['version'] ?? 'unknown'
                : null;

            $latestVersion = $versionChecker->latest($module['repo'], $module['ref'], $module['path']) ?? 'unknown';

            return [
                $module['key'],
                $module['name'],
                $module['depends_on'] === [] ? '—' : implode(', ', $module['depends_on']),
                $installedVersion === null ? 'not installed' : $installedVersion,
                $latestVersion,
            ];
        })->all();

        $this->table(['Key', 'Name', 'Depends on', 'Installed', 'Latest'], $rows);

        return self::SUCCESS;
    }
}
