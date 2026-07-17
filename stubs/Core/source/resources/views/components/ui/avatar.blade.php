@props(['name' => '', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-6 w-6 text-xs',
        'md' => 'h-8 w-8 text-sm',
        'lg' => 'h-10 w-10 text-base',
    ];

    $initial = \Illuminate\Support\Str::of($name)->trim()->substr(0, 1)->upper();
@endphp

<span {{ $attributes->merge(['class' => 'flex items-center justify-center rounded-pill bg-brand-600 font-semibold text-white ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    {{ $initial }}
</span>
