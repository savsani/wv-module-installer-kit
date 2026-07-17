<?php

/*
|--------------------------------------------------------------------------
| UI Component Variant Colors
|--------------------------------------------------------------------------
|
| Single source of truth for variant -> Tailwind class mappings shared by
| the button, badge, alert, and toast components. Each of those components
| used to define its own copy of this map; adding a variant or retheming a
| color meant editing four files that could silently drift apart. Now it's
| one file: add/rename a variant here and every component picks it up.
|
| Shapes differ per component (button has solid/outline, badge has
| light/solid, alert/toast have bg/border/icon/title/text) because each
| component composes color into different visual roles. Variant *keys*
| don't need to match 1:1 across components either — e.g. alert/toast use
| "primary" for brand-colored informational tone alongside "info" (sky),
| while button/badge additionally have a neutral "secondary".
|
*/

return [

    'button' => [
        'primary' => [
            'solid' => 'border border-transparent bg-brand-600 text-white hover:bg-brand-500 focus:ring-brand-500',
            'outline' => 'border border-brand-300 bg-white text-brand-700 hover:bg-brand-50 focus:ring-brand-500 dark:border-brand-700 dark:bg-transparent dark:text-brand-300 dark:hover:bg-brand-500/10',
        ],
        'secondary' => [
            'solid' => 'border border-transparent bg-gray-700 text-white hover:bg-gray-600 focus:ring-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 dark:focus:ring-gray-400',
            'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700',
        ],
        'success' => [
            'solid' => 'border border-transparent bg-green-600 text-white hover:bg-green-500 focus:ring-green-500',
            'outline' => 'border border-green-300 bg-white text-green-700 hover:bg-green-50 focus:ring-green-500 dark:border-green-800 dark:bg-transparent dark:text-green-400 dark:hover:bg-green-500/10',
        ],
        'danger' => [
            'solid' => 'border border-transparent bg-red-600 text-white hover:bg-red-500 focus:ring-red-500',
            'outline' => 'border border-red-300 bg-white text-red-700 hover:bg-red-50 focus:ring-red-500 dark:border-red-800 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-500/10',
        ],
        'warning' => [
            'solid' => 'border border-transparent bg-amber-500 text-white hover:bg-amber-400 focus:ring-amber-500',
            'outline' => 'border border-amber-300 bg-white text-amber-700 hover:bg-amber-50 focus:ring-amber-500 dark:border-amber-800 dark:bg-transparent dark:text-amber-400 dark:hover:bg-amber-500/10',
        ],
        'info' => [
            'solid' => 'border border-transparent bg-sky-600 text-white hover:bg-sky-500 focus:ring-sky-500',
            'outline' => 'border border-sky-300 bg-white text-sky-700 hover:bg-sky-50 focus:ring-sky-500 dark:border-sky-800 dark:bg-transparent dark:text-sky-400 dark:hover:bg-sky-500/10',
        ],
    ],

    'badge' => [
        'primary' => [
            'light' => 'bg-brand-50 text-brand-700 ring-1 ring-inset ring-brand-600/20 dark:bg-brand-500/10 dark:text-brand-300 dark:ring-brand-400/20',
            'solid' => 'bg-brand-600 text-white dark:bg-brand-500',
        ],
        'secondary' => [
            'light' => 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-300 dark:ring-gray-400/20',
            'solid' => 'bg-gray-600 text-white dark:bg-gray-500',
        ],
        'success' => [
            'light' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
            'solid' => 'bg-green-600 text-white dark:bg-green-500',
        ],
        'danger' => [
            'light' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20',
            'solid' => 'bg-red-600 text-white dark:bg-red-500',
        ],
        'warning' => [
            'light' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20',
            'solid' => 'bg-amber-500 text-white dark:bg-amber-500',
        ],
        'info' => [
            'light' => 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-600/20 dark:bg-sky-500/10 dark:text-sky-400 dark:ring-sky-500/20',
            'solid' => 'bg-sky-600 text-white dark:bg-sky-500',
        ],
    ],

    'alert' => [
        'primary' => [
            'bg' => 'bg-brand-50 dark:bg-brand-500/10',
            'border' => 'border-brand-200 dark:border-brand-500/20',
            'icon' => 'text-brand-500 dark:text-brand-400',
            'title' => 'text-brand-800 dark:text-brand-300',
            'text' => 'text-brand-700 dark:text-brand-300/80',
        ],
        'info' => [
            'bg' => 'bg-sky-50 dark:bg-sky-500/10',
            'border' => 'border-sky-200 dark:border-sky-500/20',
            'icon' => 'text-sky-500 dark:text-sky-400',
            'title' => 'text-sky-800 dark:text-sky-300',
            'text' => 'text-sky-700 dark:text-sky-300/80',
        ],
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-500/10',
            'border' => 'border-green-200 dark:border-green-500/20',
            'icon' => 'text-green-500 dark:text-green-400',
            'title' => 'text-green-800 dark:text-green-300',
            'text' => 'text-green-700 dark:text-green-300/80',
        ],
        'warning' => [
            'bg' => 'bg-amber-50 dark:bg-amber-500/10',
            'border' => 'border-amber-200 dark:border-amber-500/20',
            'icon' => 'text-amber-500 dark:text-amber-400',
            'title' => 'text-amber-800 dark:text-amber-300',
            'text' => 'text-amber-700 dark:text-amber-300/80',
        ],
        'danger' => [
            'bg' => 'bg-red-50 dark:bg-red-500/10',
            'border' => 'border-red-200 dark:border-red-500/20',
            'icon' => 'text-red-500 dark:text-red-400',
            'title' => 'text-red-800 dark:text-red-300',
            'text' => 'text-red-700 dark:text-red-300/80',
        ],
    ],

    'toast' => [
        'primary' => [
            'border' => 'border-brand-500 dark:border-brand-500/30',
            'bg' => 'bg-brand-50 dark:bg-brand-500/15',
            'icon_bg' => 'bg-brand-100 dark:bg-brand-500/25',
            'icon_text' => 'text-brand-500 dark:text-brand-400',
            'text' => 'text-brand-700 dark:text-brand-400',
        ],
        'info' => [
            'border' => 'border-sky-500 dark:border-sky-500/30',
            'bg' => 'bg-sky-50 dark:bg-sky-500/15',
            'icon_bg' => 'bg-sky-100 dark:bg-sky-500/25',
            'icon_text' => 'text-sky-500 dark:text-sky-400',
            'text' => 'text-sky-700 dark:text-sky-400',
        ],
        'success' => [
            'border' => 'border-green-500 dark:border-green-500/30',
            'bg' => 'bg-green-50 dark:bg-green-500/15',
            'icon_bg' => 'bg-green-100 dark:bg-green-500/25',
            'icon_text' => 'text-green-500 dark:text-green-400',
            'text' => 'text-green-700 dark:text-green-400',
        ],
        'warning' => [
            'border' => 'border-amber-500 dark:border-amber-500/30',
            'bg' => 'bg-amber-50 dark:bg-amber-500/15',
            'icon_bg' => 'bg-amber-100 dark:bg-amber-500/25',
            'icon_text' => 'text-amber-500 dark:text-amber-400',
            'text' => 'text-amber-700 dark:text-amber-400',
        ],
        'danger' => [
            'border' => 'border-red-500 dark:border-red-500/30',
            'bg' => 'bg-red-50 dark:bg-red-500/15',
            'icon_bg' => 'bg-red-100 dark:bg-red-500/25',
            'icon_text' => 'text-red-500 dark:text-red-400',
            'text' => 'text-red-700 dark:text-red-400',
        ],
    ],

];
