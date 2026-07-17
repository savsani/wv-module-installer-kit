<?php

namespace Wv\ModuleInstallerKit\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Wv\ModuleInstallerKit\ModuleRegistry;

class ListCommand extends Command
{
    protected $signature = 'wv:list';

    protected $description = 'List every module this package ships and whether it is installed in this app';

    public function handle(ModuleRegistry $registry, Filesystem $files): int
    {
        $rows = $registry->all()->map(function (array $module) use ($files) {
            $manifestPath = base_path($module['target']).'/.wv-manifest.json';
            $installedVersion = $files->exists($manifestPath)
                ? json_decode($files->get($manifestPath), true)['package_version'] ?? 'unknown'
                : null;

            return [
                $module['key'],
                $module['name'],
                $module['depends_on'] === [] ? '—' : implode(', ', $module['depends_on']),
                $installedVersion === null ? 'not installed' : "installed ({$installedVersion})",
                $module['version'],
            ];
        })->all();

        $this->table(['Key', 'Name', 'Depends on', 'Status', 'Available version'], $rows);

        return self::SUCCESS;
    }
}
