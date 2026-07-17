<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Filesystem\Filesystem;

class ComposerConfigPatcher
{
    public function __construct(protected Filesystem $files) {}

    /**
     * Ensure the host's composer.json merges Modules/*\/composer.json via
     * wikimedia/composer-merge-plugin — that's how a module's own PSR-4
     * mapping (e.g. Modules\Core\) gets autoloaded once copied in.
     *
     * @return bool whether composer.json changed
     */
    public function ensureModuleMergePluginConfigured(string $composerJsonPath): bool
    {
        $composer = json_decode($this->files->get($composerJsonPath), true);
        $changed = false;

        $includes = $composer['extra']['merge-plugin']['include'] ?? [];

        if (! in_array('Modules/*/composer.json', $includes, true)) {
            $includes[] = 'Modules/*/composer.json';
            $composer['extra']['merge-plugin']['include'] = $includes;
            $changed = true;
        }

        if (! isset($composer['config']['allow-plugins']['wikimedia/composer-merge-plugin'])) {
            $composer['config']['allow-plugins']['wikimedia/composer-merge-plugin'] = true;
            $changed = true;
        }

        if ($changed) {
            $this->files->put(
                $composerJsonPath,
                json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
            );
        }

        return $changed;
    }
}
