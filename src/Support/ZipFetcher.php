<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use ZipArchive;

class ZipFetcher
{
    /**
     * Download a zip snapshot of the given repo/ref and extract just one
     * module's subfolder into a fresh temp directory. No git binary
     * involved — a plain HTTPS download plus PHP's zip extension, so it
     * works anonymously against public repos.
     *
     * @return array{path: string} path to the extracted module folder
     */
    public function fetch(string $repo, string $ref, string $modulePath): array
    {
        $zipPath = sys_get_temp_dir().'/wv-module-'.uniqid().'.zip';
        $extractTo = sys_get_temp_dir().'/wv-module-'.uniqid();

        $response = Http::get("https://codeload.github.com/{$repo}/zip/{$ref}");

        if ($response->failed()) {
            throw new RuntimeException("Could not download [{$repo}@{$ref}]: HTTP {$response->status()}");
        }

        file_put_contents($zipPath, $response->body());

        $zip = new ZipArchive;

        if ($zip->open($zipPath) !== true) {
            unlink($zipPath);

            throw new RuntimeException("Downloaded archive for [{$repo}@{$ref}] is not a valid zip.");
        }

        $zip->extractTo($extractTo);
        $zip->close();
        unlink($zipPath);

        // GitHub wraps the zip contents in a single "{repo}-{ref}/" folder —
        // find it rather than assume its exact name, since the sanitized ref
        // in that folder name doesn't always match $ref verbatim.
        $root = glob($extractTo.'/*', GLOB_ONLYDIR)[0] ?? null;

        if ($root === null) {
            throw new RuntimeException("Downloaded archive for [{$repo}@{$ref}] was empty.");
        }

        $modulePath = $root.'/'.trim($modulePath, '/');

        if (! is_dir($modulePath)) {
            throw new RuntimeException("[{$modulePath}] does not exist in [{$repo}@{$ref}].");
        }

        return ['path' => $modulePath, 'root' => $extractTo];
    }
}
