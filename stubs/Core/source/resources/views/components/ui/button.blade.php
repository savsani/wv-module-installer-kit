@props([
    'variant' => 'primary',
    'style' => 'solid',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $isDisabled = $attributes->get('disabled') !== null && $attributes->get('disabled') !== false;

    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs gap-1.5',
        'sm' => 'px-3 py-2 text-sm gap-1.5',
        'md' => 'px-4 py-2.5 text-sm gap-2',
        'lg' => 'px-5 py-3 text-base gap-2',
        'xl' => 'px-6 py-3.5 text-base gap-2.5',
    ];

    $colors = config('ui.button');

    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $colorClasses = ($colors[$variant] ?? $colors['primary'])[$style] ?? $colors['primary']['solid'];

    $classes = "ui-button inline-flex items-center justify-center font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-offset-gray-900 {$sizeClasses} {$colorClasses}";
@endphp

@if($href && ! $isDisabled)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @isset($iconLeft){{ $iconLeft }}@endisset
        {{ $slot }}
        @isset($iconRight){{ $iconRight }}@endisset
    </a>
@else
    <button type="{{ $type }}" @if($isDisabled) disabled @endif {{ $attributes->except('disabled')->merge(['class' => $classes]) }}>
        @isset($iconLeft){{ $iconLeft }}@endisset
        {{ $slot }}
        @isset($iconRight){{ $iconRight }}@endisset
    </button>
@endif
