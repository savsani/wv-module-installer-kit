<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Filesystem\Filesystem;

class NodeDependencyMerger
{
    public function __construct(protected Filesystem $files) {}

    /**
     * Merge a module's declared npm dependencies into the host app's
     * package.json. Additive only — an existing pinned version in the host
     * always wins, this never downgrades or overwrites it.
     *
     * @return bool whether package.json changed
     */
    public function merge(string $packageJsonPath, string $depsManifestPath): bool
    {
        if (! $this->files->exists($depsManifestPath)) {
            return false;
        }

        $declared = json_decode($this->files->get($depsManifestPath), true) ?? [];
        $target = json_decode($this->files->get($packageJsonPath), true) ?? [];

        $changed = false;

        foreach (['dependencies', 'devDependencies'] as $group) {
            foreach ($declared[$group] ?? [] as $package => $version) {
                if (! isset($target[$group][$package])) {
                    $target[$group][$package] = $version;
                    $changed = true;
                }
            }
        }

        if ($changed) {
            $this->files->put(
                $packageJsonPath,
                json_encode($target, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
            );
        }

        return $changed;
    }
}
