{{--
    Generic "nothing here yet" placeholder. Pass `title`/`description` for the
    common static case, or provide the default slot for fully custom (e.g.
    Alpine-reactive) content:

        <x-ui.empty-state title="No projects yet" description="Create your first project to get started.">
            <x-slot:icon><x-icon.squares-2x2 class="h-6 w-6" /></x-slot:icon>
            <x-slot:actions><x-ui.button size="sm">New Project</x-ui.button></x-slot:actions>
        </x-ui.empty-state>

    Pass `:bordered="false"` when nesting inside an existing bordered
    container (e.g. a table's empty row) to drop the dashed border.
--}}
@props([
    'title' => 'Nothing here yet',
    'description' => null,
    'bordered' => true,
])

@php
    $classes = 'flex flex-col items-center justify-center gap-3 px-6 py-12 text-center';
    if ($bordered) {
        $classes .= ' ui-empty-state';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @isset($icon)
        <div class="flex h-12 w-12 items-center justify-center rounded-pill bg-gray-100 text-gray-400 dark:bg-white/5 dark:text-gray-500">
            {{ $icon }}
        </div>
    @endisset

    @if($slot->isNotEmpty())
        {{ $slot }}
    @else
        <div>
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $title }}</p>
            @if($description)
                <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">{{ $description }}</p>
            @endif
        </div>
    @endif

    @isset($actions)
        <div class="mt-1">{{ $actions }}</div>
    @endisset
</div>
