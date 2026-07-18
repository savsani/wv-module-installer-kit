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
        'auth' => [
            'name' => 'Auth',
            'description' => 'Fortify-backed authentication: login, registration, password reset/confirmation, email verification, two-factor auth, and profile management.',
            'depends_on' => ['core'],
            'path' => 'Auth',
            'target' => 'Modules/Auth',
            'npm' => null,
        ],
        'admin' => [
            'name' => 'Admin',
            'description' => 'User, role, and permission management, user impersonation, and a module manager UI.',
            'depends_on' => ['core', 'auth'],
            'path' => 'Admin',
            'target' => 'Modules/Admin',
            'npm' => null,
        ],
        'activitylog' => [
            'name' => 'ActivityLog',
            'description' => 'Records and displays an audit trail of actions across the app, decoupled from other modules via a shared event.',
            'depends_on' => ['core'],
            'path' => 'ActivityLog',
            'target' => 'Modules/ActivityLog',
            'npm' => null,
        ],
    ],

];
