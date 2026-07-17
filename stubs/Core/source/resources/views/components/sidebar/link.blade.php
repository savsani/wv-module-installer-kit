@props(['href', 'active' => false])

<a
    href="{{ $href }}"
    class="flex items-center gap-3 overflow-hidden rounded-lg px-3 py-2 text-sm font-medium whitespace-nowrap transition focus:outline-none {{ $active ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}"
    :class="!expanded && 'lg:justify-center'"
>
    {{ $icon }}
    <span
        x-show="expanded"
        x-transition:enter="transition ease-out duration-150 delay-75"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >{{ $slot }}</span>
</a>
