{{--
    Toolbar strip that groups icon-toggle buttons/dropdowns; combine with
    <x-ui.richtext.icon-toggle-divider> between clusters.

    `plain` (default true) matches the plain <x-ui.card-section> body — no
    visible toolbar background of its own. `plain="false"` gives the toolbar
    its own tinted background (same family as the number-stepper buttons,
    file-input's "Choose file" chip, and the rich-text editor's own toolbar —
    all `bg-gray-50 dark:bg-gray-800`), for toolbars that need to read as a
    distinct bar rather than blend into the surrounding surface.
--}}
@props(['plain' => true])

@php
    $surfaceClasses = $plain ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800';
@endphp

<div {{ $attributes->merge(['class' => "ui-icon-toggle-group inline-flex flex-wrap items-center gap-0.5 border border-gray-200 {$surfaceClasses} p-1 dark:border-gray-800"]) }}>
    {{ $slot }}
</div>
