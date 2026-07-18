<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Support\Facades\Http;

class ModuleVersionChecker
{
    /**
     * Read just a module's wv-module.json version field straight off
     * raw.githubusercontent.com — a single small file over plain HTTP,
     * not a full zip download. Used to decide whether an install/update
     * actually needs to fetch anything.
     */
    public function latest(string $repo, string $ref, string $modulePath): ?string
    {
        $url = "https://raw.githubusercontent.com/{$repo}/{$ref}/".trim($modulePath, '/').'/wv-module.json';

        $response = Http::get($url);

        if ($response->failed()) {
            return null;
        }

        return $response->json('version');
    }
}
