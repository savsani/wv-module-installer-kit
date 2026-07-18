<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Support\Facades\Process;
use RuntimeException;

class RepositoryFetcher
{
    /**
     * Shallow-clone a module's repo at the given ref into a fresh temp
     * directory. Caller is responsible for deleting the returned path once
     * done with it.
     *
     * @return array{path: string, commit: string}
     */
    public function fetch(string $repo, string $ref): array
    {
        $path = sys_get_temp_dir().'/wv-module-'.uniqid();

        $clone = Process::run(['git', 'clone', '--quiet', '--depth', '1', '--branch', $ref, $repo, $path]);

        if ($clone->failed()) {
            throw new RuntimeException("Could not clone [{$repo}] at ref [{$ref}]: {$clone->errorOutput()}");
        }

        $commit = Process::path($path)->run(['git', 'rev-parse', 'HEAD'])->output();

        return ['path' => $path, 'commit' => trim($commit)];
    }
}
