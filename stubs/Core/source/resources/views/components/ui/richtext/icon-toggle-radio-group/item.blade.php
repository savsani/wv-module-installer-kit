@props([
    'value',
    'size' => 'md',
    'label' => null,
    'command' => null,
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
    @click="{!! $command ?? "selected = '{$value}'" !!}"
    :aria-pressed="(selected === '{{ $value }}').toString()"
    x-bind:class="selected === '{{ $value }}'
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
