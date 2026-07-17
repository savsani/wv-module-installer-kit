<?php

namespace Wv\ModuleInstallerKit\Support;

use Illuminate\Filesystem\Filesystem;

class StubCopier
{
    public function __construct(protected Filesystem $files) {}

    public function copy(string $source, string $target): void
    {
        if ($this->files->isDirectory($target)) {
            $this->files->deleteDirectory($target);
        }

        $this->files->copyDirectory($source, $target);
    }
}
