{{--
    Dashboard KPI tile: icon, label, value, and an optional up/down change badge.

        <x-ui.stat-card label="Total Revenue" value="$48,900" change="+12.4%" trend="up" variant="success">
            <x-slot:icon><x-icon.chart-bar class="h-5 w-5" /></x-slot:icon>
        </x-ui.stat-card>
--}}
@props([
    'label',
    'value',
    'change' => null,
    'trend' => 'up',
    'variant' => 'primary',
])

@php
    $iconColors = [
        'primary' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400',
        'secondary' => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
        'success' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
        'danger' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
        'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
        'info' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400',
    ];
    $iconColor = $iconColors[$variant] ?? $iconColors['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'ui-card flex items-center gap-4 p-5']) }}>
    @isset($icon)
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-control {{ $iconColor }}">
            {{ $icon }}
        </div>
    @endisset

    <div class="min-w-0">
        <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $value }}</p>

        @if($change !== null)
            <x-ui.badge :variant="$trend === 'down' ? 'danger' : 'success'" size="sm" class="mt-2">
                <x-slot:iconLeft>
                    @if($trend === 'down')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    @endif
                </x-slot:iconLeft>
                {{ $change }}
            </x-ui.badge>
        @endif
    </div>
</div>
