<?php

namespace Wv\ModuleInstallerKit;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class ModuleRegistry
{
    /**
     * @var Collection<string, array{key: string, name: string, description: string, depends_on: array<int, string>, target: string, source: string, npm: ?string, version: string}>|null
     */
    protected ?Collection $modules = null;

    public function __construct(protected string $stubsPath) {}

    /**
     * Every module this package ships, discovered from stubs/*\/wv-module.json.
     * Adding a new module later is dropping a folder here — nothing to
     * register centrally.
     *
     * @return Collection<string, array{key: string, name: string, description: string, depends_on: array<int, string>, target: string, source: string, npm: ?string, version: string}>
     */
    public function all(): Collection
    {
        if ($this->modules !== null) {
            return $this->modules;
        }

        $descriptors = glob($this->stubsPath.'/*/wv-module.json') ?: [];

        return $this->modules = collect($descriptors)
            ->map(fn (string $path) => $this->describe($path))
            ->keyBy('key');
    }

    public function find(string $key): ?array
    {
        return $this->all()->get($key);
    }

    /**
     * Expand the given module keys through their `depends_on` chain and
     * return them in dependency-first order, without duplicates — so
     * `wv:install auth` also pulls in `core` first once Auth ships.
     *
     * @param  array<int, string>  $keys
     * @return array<int, array{key: string, name: string, description: string, depends_on: array<int, string>, target: string, source: string, npm: ?string, version: string}>
     */
    public function resolveWithDependencies(array $keys): array
    {
        $resolved = [];
        $seen = [];

        $visit = function (string $key) use (&$visit, &$resolved, &$seen): void {
            if (isset($seen[$key])) {
                return;
            }

            $module = $this->find($key);

            if (! $module) {
                throw new InvalidArgumentException("Unknown module [{$key}]. Run `php artisan wv:list` to see available modules.");
            }

            $seen[$key] = true;

            foreach ($module['depends_on'] as $dependency) {
                $visit($dependency);
            }

            $resolved[] = $module;
        };

        foreach ($keys as $key) {
            $visit($key);
        }

        return $resolved;
    }

    /**
     * @return array{key: string, name: string, description: string, depends_on: array<int, string>, target: string, source: string, npm: ?string, version: string}
     */
    protected function describe(string $descriptorPath): array
    {
        $moduleDir = dirname($descriptorPath);
        $descriptor = json_decode(file_get_contents($descriptorPath), true);

        return [
            'key' => $descriptor['key'],
            'name' => $descriptor['name'],
            'description' => $descriptor['description'] ?? '',
            'depends_on' => $descriptor['depends_on'] ?? [],
            'source' => $moduleDir.'/'.($descriptor['source'] ?? 'source'),
            'target' => $descriptor['target'],
            'npm' => isset($descriptor['npm']) ? $moduleDir.'/'.$descriptor['npm'] : null,
            'version' => $descriptor['version'] ?? '0.1.0',
        ];
    }
}
