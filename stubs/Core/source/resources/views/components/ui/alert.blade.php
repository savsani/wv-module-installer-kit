@props([
    'variant' => 'info',
    'title' => null,
    'icon' => true,
    'dismissible' => false,
])

@php
    $colors = config('ui.alert');

    $icons = [
        'primary' => 'information-circle',
        'info' => 'information-circle',
        'success' => 'check-circle',
        'warning' => 'exclamation-triangle',
        'danger' => 'x-circle',
    ];

    $c = $colors[$variant] ?? $colors['info'];
    $iconComponent = $icons[$variant] ?? 'information-circle';
    $role = in_array($variant, ['danger', 'warning']) ? 'alert' : 'status';
@endphp

<div
    role="{{ $role }}"
    @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif
    {{ $attributes->merge(['class' => "ui-alert border p-4 {$c['bg']} {$c['border']}"]) }}
>
    <div class="flex items-center gap-3">
        @if($icon)
            <div class="shrink-0 {{ $c['icon'] }}">
                <x-dynamic-component :component="'icon.' . $iconComponent" class="h-5 w-5" />
            </div>
        @endif

        <div class="flex min-w-0 flex-1 items-center justify-between gap-2">
            @if($title)
                <h3 class="text-sm font-semibold {{ $c['title'] }}">{{ $title }}</h3>
            @endif

            @if($dismissible)
                <button
                    type="button"
                    @click="show = false"
                    class="shrink-0 rounded-control p-1 {{ $c['icon'] }} transition hover:bg-black/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-current focus-visible:ring-offset-1 dark:hover:bg-white/5 dark:focus-visible:ring-offset-gray-900 {{ $title ? '' : 'ml-auto' }}"
                    aria-label="Dismiss"
                >
                    <x-icon.x-mark class="h-4 w-4" />
                </button>
            @endif
        </div>
    </div>

    <div class="text-sm {{ $c['text'] }} {{ $title ? 'mt-1' : '' }} {{ $icon ? 'pl-8' : '' }}">
        {{ $slot }}
    </div>
</div>
