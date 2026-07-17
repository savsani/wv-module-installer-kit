@props(['variant' => 'default'])

@php
    $colorClasses = $variant === 'danger'
        ? 'text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10'
        : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5';
@endphp

<button type="button" {{ $attributes->merge(['class' => "flex w-full items-center gap-2.5 px-3.5 py-2 text-left text-sm transition {$colorClasses}"]) }}>
    @isset($icon)
        <span class="shrink-0">{{ $icon }}</span>
    @endisset
    {{ $slot }}
</button>
