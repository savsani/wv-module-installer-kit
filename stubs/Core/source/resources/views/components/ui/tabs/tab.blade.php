@props(['value'])

@php
    $activeHorizontal = 'border-brand-600 text-brand-600 dark:border-brand-400 dark:text-brand-400';
    $inactiveHorizontal = 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200';
    $activeVertical = 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400';
    $inactiveVertical = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200';
@endphp

<button
    type="button"
    role="tab"
    @click="tab = '{{ $value }}'"
    :aria-selected="(tab === '{{ $value }}').toString()"
    {{ $attributes->merge(['class' => 'flex shrink-0 cursor-pointer items-center gap-2 text-sm font-medium whitespace-nowrap transition focus:outline-none']) }}
    :class="{
        '-mb-px border-b-2 px-1 py-3 {{ $activeHorizontal }}': orientation === 'horizontal' && tab === '{{ $value }}',
        '-mb-px border-b-2 px-1 py-3 {{ $inactiveHorizontal }}': orientation === 'horizontal' && tab !== '{{ $value }}',
        'w-full justify-start ui-tabs px-3 py-2 {{ $activeVertical }}': orientation === 'vertical' && tab === '{{ $value }}',
        'w-full justify-start ui-tabs px-3 py-2 {{ $inactiveVertical }}': orientation === 'vertical' && tab !== '{{ $value }}',
    }"
>
    @isset($icon)
        <span class="h-4 w-4 shrink-0">{{ $icon }}</span>
    @endisset

    {{ $slot }}
</button>
