# wv/module-installer-kit

Installs and updates Wv's reusable Laravel modules (Core, and future modules) as first-class, editable code inside a host app's `Modules/` directory.

Each module lives in its own GitHub repo (e.g. [`wv-core-module`](https://github.com/savsani/wv-core-module)). This package doesn't vendor that code — on install/update it clones the module's repo into a temp directory and copies it into `Modules/{Name}`. Once copied, the module is plain app code: no vendor lock, safe to hand-edit, tracked by your own git repo like everything else in `app/` or `resources/`.

## Requirements

- The module repos are cloned over `git`, so the machine running these commands needs working GitHub credentials (SSH key or a cached HTTPS credential) with access to each module's repo.

## Commands

### `php artisan wv:list`

Shows every module this package knows about (from `config/wv-modules.php`), whether it's currently installed in this app, which commit is installed, and which repo/ref it tracks.

```bash
php artisan wv:list
```

```
+------+------+------------+------------------------+---------------------------------------------------+
| Key  | Name | Depends on | Status                 | Source                                             |
+------+------+------------+------------------------+---------------------------------------------------+
| core | Core | —          | installed (d1bf3b878c) | https://github.com/savsani/wv-core-module.git@main |
+------+------+------------+------------------------+---------------------------------------------------+
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

Re-fetches the latest commit on each module's tracked ref and **completely overwrites** the corresponding `Modules/{Name}` directory — it's a wholesale replace, not a merge, so any local edits inside that module directory are lost. Only modules that are already installed are updated; anything not installed is skipped with a hint to `wv:install` it first.

```bash
# Update one module
php artisan wv:update core

# Update everything currently installed
php artisan wv:update --all
```

| Option | Effect |
|---|---|
| `--all` | Update every installed module instead of naming keys |
| `--force` | Skip the "this will overwrite local edits" confirmation prompt |

### Removing a module

There's no `wv:remove` command yet — to remove a module today, delete `Modules/{Name}` yourself and drop its entry from `modules_statuses.json`. Ask if you want an automated `wv:remove` added.

## Registering a module

Modules are declared in [`config/wv-modules.php`](config/wv-modules.php), keyed by module slug:

```php
'core' => [
    'name' => 'Core',
    'description' => 'Shared UI kit, design tokens, layouts, and Alpine.js components every other Wv module builds on.',
    'depends_on' => [],                                        // other module keys required first
    'repo' => 'https://github.com/savsani/wv-core-module.git',
    'ref' => 'main',                                            // branch or tag to track
    'target' => 'Modules/Core',                                 // where it's copied to in the host app
    'npm' => 'package.deps.json',                               // npm deps file at the repo root, or null
],
```

A consuming app can publish and override this file to pin a module to a specific tag, add a private fork, or register a module this package doesn't know about yet:

```bash
php artisan vendor:publish --tag=wv-modules-config
```

### Module repo layout

Each module's own repo mirrors what gets copied:

```
package.deps.json   # optional — npm deps merged into the host's package.json
source/              # copied verbatim into Modules/{Name}
  module.json
  composer.json
  app/
  resources/
  ...
```
