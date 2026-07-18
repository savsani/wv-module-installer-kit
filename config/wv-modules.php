<?php

return [

    // Shared source for every module below — one repo, one ref, regardless
    // of how many modules this grows to.
    'repo' => 'savsani/wv-modules',
    'ref' => 'main',

    'modules' => [
        'core' => [
            'name' => 'Core',
            'description' => 'Shared UI kit, design tokens, layouts, and Alpine.js components every other Wv module builds on.',
            'depends_on' => [],
            'path' => 'Core',
            'target' => 'Modules/Core',
            'npm' => 'package.deps.json',
        ],
    ],

];
