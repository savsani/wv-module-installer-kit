<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ManifestWriter
{
    public function __construct(protected Filesystem $files) {}

    /**
     * Records which version of the module a module directory was synced
     * from and a content hash per file. Updates always overwrite regardless
     * of this manifest — it isn't a "skip if customized" gate — but it's
     * the foundation for the safe-customization tooling and clean-uninstall
     * support planned for later.
     */
    public function write(string $moduleDirectory, string $moduleKey, string $version): void
    {
        $hashes = [];

        foreach (Finder::create()->files()->in($moduleDirectory)->notName('.wv-manifest.json') as $file) {
            $hashes[$file->getRelativePathname()] = hash_file('sha256', $file->getRealPath());
        }

        $this->files->put(
            $moduleDirectory.'/.wv-manifest.json',
            json_encode([
                'module' => $moduleKey,
                'version' => $version,
                'synced_at' => now()->toIso8601String(),
                'files' => $hashes,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
        );
    }
}
