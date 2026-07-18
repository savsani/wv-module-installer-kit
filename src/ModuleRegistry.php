<?php

namespace Wv\ModuleInstallerKit;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class ModuleRegistry
{
    /**
     * @var Collection<string, array{key: string, name: string, description: string, depends_on: array<int, string>, target: string, repo: string, ref: string, npm: ?string}>|null
     */
    protected ?Collection $modules = null;

    /**
     * @param  array<string, array{name: string, description?: string, depends_on?: array<int, string>, target: string, repo: string, ref?: string, npm?: string}>  $config
     */
    public function __construct(protected array $config) {}

    /**
     * Every module this package knows how to install, from config/wv-modules.php.
     * Adding a new module later is adding an entry here — nothing else to
     * register centrally.
     *
     * @return Collection<string, array{key: string, name: string, description: string, depends_on: array<int, string>, target: string, repo: string, ref: string, npm: ?string}>
     */
    public function all(): Collection
    {
        if ($this->modules !== null) {
            return $this->modules;
        }

        return $this->modules = collect($this->config)
            ->map(fn (array $module, string $key) => [
                'key' => $key,
                'name' => $module['name'],
                'description' => $module['description'] ?? '',
                'depends_on' => $module['depends_on'] ?? [],
                'repo' => $module['repo'],
                'ref' => $module['ref'] ?? 'main',
                'target' => $module['target'],
                'npm' => $module['npm'] ?? null,
            ]);
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
     * @return array<int, array{key: string, name: string, description: string, depends_on: array<int, string>, target: string, repo: string, ref: string, npm: ?string}>
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
}
