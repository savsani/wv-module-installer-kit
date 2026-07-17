@props(['value', 'command' => null])

<li
    @click="{!! $command ?? "selected = '{$value}'" !!}; open = false"
    role="option"
    :aria-selected="(selected === '{{ $value }}').toString()"
    x-bind:class="selected === '{{ $value }}'
        ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400'
        : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5'"
    {{ $attributes->merge(['class' => 'flex cursor-pointer items-center gap-2.5 px-3.5 py-2 text-sm transition']) }}
>
    @isset($icon)
        <span class="shrink-0">{{ $icon }}</span>
    @endisset

    <span class="flex-1">{{ $slot }}</span>

    <x-icon.check x-show="selected === '{{ $value }}'" x-cloak class="h-3.5 w-3.5 shrink-0 stroke-brand-500 dark:stroke-brand-400" />
</li>
