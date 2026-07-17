{{--
    Icon-only toggle button for editor/toolbar controls (bold, italic, underline...).
    Self-contained active state by default — click flips it, hover/idle and
    active colors follow the same brand-accent used for "selected" states
    elsewhere (tabs, radio-group, search-select).

    Pass `command` (an Alpine expression run on click, e.g. "toggleBold()")
    and `sync` (an Alpine expression read into the local `active` state via
    x-effect, e.g. "isActive('bold')") to bind this to a real editor instead
    — see <x-form.rich-text-editor>, which wires every control in
    <x-form.rich-text-toolbar :bound="true"> this way.
--}}
@props([
    'pressed' => false,
    'size' => 'md',
    'label' => null,
    'command' => null,
    'sync' => null,
])

@php
    $sizes = [
        'sm' => 'h-8 w-8',
        'md' => 'h-9 w-9',
        'lg' => 'h-10 w-10',
    ];

    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

<button
    type="button"
    x-data="{ active: {{ $pressed ? 'true' : 'false' }} }"
    @if($sync) x-effect="active = ({!! $sync !!})" @endif
    @click="{!! $command ?? 'active = ! active' !!}"
    :aria-pressed="active.toString()"
    x-bind:class="active
        ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400'
        : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200'"
    @if($label)
        aria-label="{{ $label }}"
        title="{{ $label }}"
    @endif
    {{ $attributes->merge(['class' => "ui-icon-toggle inline-flex shrink-0 cursor-pointer items-center justify-center transition focus:outline-none {$sizeClasses}"]) }}
>
    {{ $slot }}
</button>
