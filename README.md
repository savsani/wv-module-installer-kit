# wv/module-installer-kit

Installs and updates Wv's reusable Laravel modules (Core, and future modules) as first-class, editable code inside a host app's `Modules/` directory.

Each module lives in its own GitHub repo (e.g. [`wv-core-module`](https://github.com/savsani/wv-core-module)). This package doesn't vendor that code — on install/update it clones the module's repo into a temp directory and copies it into `Modules/{Name}`. Once copied, the module is plain app code: no vendor lock, safe to hand-edit, tracked by your own git repo like everything else in `app/` or `resources/`.

## Requirements

- The module repos are cloned over `git`, so the machine running these commands needs working GitHub credentials (SSH key or a cached HTTPS credential) with access to each module's repo.

## Installing this package in a host Laravel app

This package itself is a private GitHub repo, not on Packagist, so a host app needs a `vcs` repository entry pointing at it before `composer require` will find it.

1. Add the repository to the host app's `composer.json`:

    ```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/savsani/wv-module-installer-kit.git"
        }
    ]
    ```

2. Since the repo is private, Composer needs its own GitHub token (separate from `git`'s cached credential — Composer doesn't read the macOS Keychain). Generate a token with `repo` scope (or a fine-grained token scoped to at least `wv-module-installer-kit` and every module repo it'll fetch) and register it once per machine:

    ```bash
    composer config --global github-oauth.github.com <your-token>
    ```

3. Require the package. There are no tagged releases yet, so pin the branch directly:

    ```bash
    composer require wv/module-installer-kit:dev-main
    ```

That's it — `wv:install`, `wv:update`, and `wv:list` are now available via `php artisan`.

### Updating the package later

```bash
composer update wv/module-installer-kit
```

This pulls the latest commit on `main` for the installer itself (the `InstallCommand`/`UpdateCommand`/registry code). It's separate from `php artisan wv:update`, which re-fetches a *module's* content (e.g. Core) — updating this package doesn't touch anything already copied into `Modules/`.

If module repos ever get tagged releases instead of tracking `main`, this step turns into an ordinary `composer require wv/module-installer-kit:^1.0` with semver — no more `dev-main` pinning or `minimum-stability` concerns.

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

# Update eve
