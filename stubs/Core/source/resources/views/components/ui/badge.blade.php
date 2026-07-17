@props([
    'variant' => 'primary',
    'style' => 'light',
    'size' => 'md',
    'pill' => true,
])

@php
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs gap-1',
        'md' => 'px-2.5 py-1 text-xs gap-1.5',
        'lg' => 'px-3 py-1 text-sm gap-1.5',
    ];

    $colors = config('ui.badge');

    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $colorClasses = ($colors[$variant] ?? $colors['primary'])[$style] ?? $colors['primary']['light'];
    $radius = $pill ? 'rounded-pill' : 'ui-badge';

    $classes = "inline-flex items-center font-medium {$radius} {$sizeClasses} {$colorClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @isset($iconLeft){{ $iconLeft }}@endisset
    {{ $slot }}
    @isset($iconRight){{ $iconRight }}@endisset
</span>
