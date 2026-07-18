# wv/module-installer-kit

Installs and updates Wv's reusable Laravel modules (Core, and future modules) as first-class, editable code inside a host app's `Modules/` directory.

Every module's source lives in one shared content repo, [`wv-modules`](https://github.com/savsani/wv-modules) — one top-level folder per module. This package doesn't vendor that code and never grows with it: on install/update it downloads a plain zip snapshot of `wv-modules`, extracts just the one requested module's folder, and discards the rest. Once copied into `Modules/{Name}`, the module is plain app code: no vendor lock, safe to hand-edit, tracked by your own git repo like everything else in `app/` or `resources/`.

Adding module #51 later means adding a folder to `wv-modules` — never a new repo, never a change to this package beyond a one-line registry entry.

## Requirements

- Both this package's repo and `wv-modules` are **public**. No GitHub token, SSH key, or `git` binary is needed anywhere — module content is fetched with a plain HTTPS GET and extracted with PHP's `ext-zip`.

## Installing this package in a host Laravel app

This package isn't on Packagist, so a host app needs a one-time `vcs` repository entry pointing at it before `composer require` will find it — nothing beyond that, no auth setup. It's dev-time tooling only (nothing at runtime depends on it once modules are copied in), so it belongs in `require-dev`.

1. Add the repository to the host app's `composer.json`:

    ```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/savsani/wv-module-installer-kit.git"
        }
    ]
    ```

2. Require the package as a dev dependency. There are no tagged releases yet, so pin the branch directly:

    ```bash
    composer require --dev wv/module-installer-kit:dev-main
    ```

That's it — `wv:install`, `wv:update`, and `wv:list` are now available via `php artisan`. Because it's `require-dev`, `composer install --no-dev` in production skips it entirely.

### Updating the package later

```bash
composer update wv/module-installer-kit
```

This pulls the latest commit on `main` for the installer itself (the `InstallCommand`/`UpdateCommand`/registry code). It's separate from `php artisan wv:update`, which re-fetches a *module's* content (e.g. Core) — updating this package doesn't touch anything already copied into `Modules/`.

If this package ever gets tagged releases instead of tracking `main`, this step turns into an ordinary `composer require wv/module-installer-kit:^1.0` with semver — no more `dev-main` pinning.

## Commands

### `php artisan wv:list`

Shows every module this package knows about (from `config/wv-modules.php`), the version installed in this app, and the latest version available in `wv-modules`.

```bash
php artisan wv:list
```

```
+------+------+------------+-----------+--------+
| Key  | Name | Depends on | Installed | Latest |
+------+------+------------+-----------+--------+
| core | Core | —          | 1.0.0     | 1.0.0  |
+------+------+------------+-----------+--------+
```

### `php artisan wv:install {modules?*} [--all] [--force]`

Fetches one or more modules and copies them into `Modules/{Name}`. Also merges the module's npm dependencies into `package.json`, adds its Vite entry points, wires up the `Modules/*/composer.json` merge-plugin config, marks the module enabled in `modules_statuses.json`, and runs `composer dump-autoload`.

```bash
# Install a single module by key
php artisan wv:install core

# Install multiple at once
php artisan wv:install core auth

# Install everything this package knows about
php artisan wv:install --all
```

| Option | Effect |
|---|---|
| `--all` | Install every module in the registry instead of naming keys |
| `--force` | Overwrite `Modules/{Name}` if it already exists (⚠️ destroys any local edits in that directory) |

Without `--force`, installing into a directory that already exists is skipped with a warning telling you to use `--force` or `wv:update` instead.

Dependencies declared via a module's `depends_on` are resolved and installed first automatically — e.g. once `auth` depends on `core`, `wv:install auth` pulls in `core` too.

### `php artisan wv:update {modules?*} [--all] [--force]`

Checks each installed module's **version** (a single small file fetched cheaply, no zip download) against what's recorded from its last install. Only modules whose version actually changed are re-fetched and copied — anything already up to date is reported and left untouched. This is a wholesale replace of `Modules/{Name}`, not a merge, so any local edits inside that module directory are lost when a module *is* updated. Modules that aren't installed yet are skipped with a hint to `wv:install` them first.

```bash
# Update one module (no-op if its version hasn't changed)
php artisan wv:update core

# Update everything currently installed
php artisan wv:update --all
```

| Option | Effect |
|---|---|
| `--all` | Check/update every installed module instead of naming keys |
| `--force` | Re-copy even if the version hasn't changed, and skip the "this will overwrite local edits" confirmation prompt |

### Removing a module

There's no `wv:remove` command yet — to remove a module today, delete `Modules/{Name}` yourself and drop its entry from `modules_statuses.json`. Ask if you want an automated `wv:remove` added.

## Registering a module

Modules are declared in [`config/wv-modules.php`](config/wv-modules.php). `repo`/`ref` at the top are shared by every module; each module just names its own subfolder in that repo:

```php
return [
    'repo' => 'savsani/wv-modules',
    'ref' => 'main',

    'modules' => [
        'core' => [
            'name' => 'Core',
            'description' => 'Shared UI kit, design tokens, layouts, and Alpine.js components every other Wv module builds on.',
            'depends_on' => [],       // other module keys required first
            'path' => 'Core',          // this module's folder inside wv-modules
            'target' => 'Modules/Core', // where it's copied to in the host app
            'npm' => 'package.deps.json', // npm deps file, relative to `path`, or null
        ],
    ],
];
```

A module can override `repo`/`ref` individually (e.g. to point at a private fork) — anything set on the module wins over the shared default.

A consuming app can publish and edit this file to pin to a specific ref, add a fork, or register a module this package doesn't know about yet:

```bash
php artisan vendor:publish --tag=wv-modules-config
```

### Module folder layout (inside `wv-modules`)

```
Core/
  wv-module.json      # { "version": "1.2.0" } — bump this on every change
  package.deps.json    # optional — npm deps merged into the host app's package.json
  source/              # copied verbatim into Modules/Core
    module.json
    composer.json
    app/
    resources/
    ...
```

**Bump `wv-module.json`'s `version` in the same commit as any change to `source/`.** `wv:update` compares versions, not file contents — if the version doesn't change, host apps won't see the update.
