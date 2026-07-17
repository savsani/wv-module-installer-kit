@props(['label', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center gap-3 overflow-hidden rounded-lg px-3 py-2 text-sm font-medium whitespace-nowrap transition focus:outline-none {{ $active ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}"
        :class="!expanded && 'lg:justify-center'"
    >
        {{ $icon }}
        <span
            class="flex-1 truncate text-left"
            x-show="expanded"
            x-transition:enter="transition ease-out duration-150 delay-75"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >{{ $label }}</span>
        <x-icon.chevron-down
            x-show="expanded"
            x-transition:enter="transition ease-out duration-150 delay-75"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="h-4 w-4 shrink-0 transition-transform duration-150"
            x-bind:class="open && 'rotate-180'"
        />
    </button>

    <div
        x-show="open && expanded"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="mt-1 space-y-1 pl-8"
    >
        {{ $slot }}
    </div>
</div>
