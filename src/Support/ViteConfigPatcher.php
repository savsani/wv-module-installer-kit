<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Filesystem\Filesystem;

class ViteConfigPatcher
{
    public function __construct(protected Filesystem $files) {}

    /**
     * Best-effort insertion of a module's Vite entry points into the host's
     * `input: [...]` array. Only handles the standard laravel-vite-plugin
     * shape — a customized vite.config.js is left untouched and reported
     * back so the entries can be added by hand instead of risking a corrupt
     * JS file from a blind rewrite.
     *
     * @param  array<int, string>  $entries
     * @return array{patched: array<int, string>, alreadyPresent: array<int, string>, needsManualStep: array<int, string>}
     */
    public function patch(string $viteConfigPath, array $entries): array
    {
        $result = ['patched' => [], 'alreadyPresent' => [], 'needsManualStep' => []];

        if (! $this->files->exists($viteConfigPath)) {
            $result['needsManualStep'] = $entries;

            return $result;
        }

        $contents = $this->files->get($viteConfigPath);

        foreach ($entries as $entry) {
            if (str_contains($contents, $entry)) {
                $result['alreadyPresent'][] = $entry;

                continue;
            }

            if (! preg_match('/(input:\s*\[)/', $contents)) {
                $result['needsManualStep'][] = $entry;

                continue;
            }

            $contents = preg_replace('/(input:\s*\[)/', "$1\n            '{$entry}',", $contents, 1);
            $result['patched'][] = $entry;
        }

        if ($result['patched'] !== []) {
            $this->files->put($viteConfigPath, $contents);
        }

        return $result;
    }
}
