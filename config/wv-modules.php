<?php

return [
    'core' => [
        'name' => 'Core',
        'description' => 'Shared UI kit, design tokens, layouts, and Alpine.js components every other Wv module builds on.',
        'depends_on' => [],
        'repo' => 'git@github.com:savsani/wv-core-module.git',
        'ref' => 'main',
        'target' => 'Modules/Core',
        'npm' => 'package.deps.json',
    ],
];
