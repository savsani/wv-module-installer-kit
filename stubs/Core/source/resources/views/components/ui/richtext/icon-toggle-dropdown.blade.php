{{--
    Icon-toggle button that opens a single-select dropdown menu (text align,
    font size, typography...). Same open/close/outside-click/escape mechanics
    as <x-navigation.dropdown>, plus a `selected` value shared with child
    <x-ui.richtext.icon-toggle-dropdown.item> rows.

    The trigger face is a named slot so the caller decides whether it stays
    fixed (a generic icon) or swaps to reflect the current selection — swap
    by giving each candidate icon `x-show="selected === 'value'"`:

        <x-ui.richtext.icon-toggle-dropdown label="Text align" default="left">
            <x-slot:trigger>
                <x-icon.align-left class="h-4 w-4" x-show="selected === 'left'" />
                <x-icon.align-center class="h-4 w-4" x-show="selected === 'center'" />
            </x-slot:trigger>

            <x-ui.richtext.icon-toggle-dropdown.item value="left">
                <x-slot:icon><x-icon.align-left class="h-4 w-4" /></x-slot:icon>
                Left
            </x-ui.richtext.icon-toggle-dropdown.item>
        </x-ui.richtext.icon-toggle-dropdown>
--}}
@props([
    'label' => null,
    'default' => null,
    'align' => 'left',
    'width' => 'w-44',
    'maxHeight' => 'max-h-64',
    'sync' => null,
])

@php
    $alignmentClasses = $align === 'right' ? 'right-0 left-auto' : 'left-0';
@endphp

<div
    class="relative inline-block"
    x-data="{ open: false, selected: @js($default) }"
    @if($sync) x-effect="selected = ({!! $sync !!})" @endif
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    {{--
        Explicit escape hatch for nested content (e.g. color-picker-more's
        own colorPicker() x-data) to close this dropdown without relying on
        Alpine's ancestor-scope write-through for `open` — dispatch
        $dispatch('close-dropdown') from anywhere inside instead.
    --}}
    @close-dropdown="open = false"
>
    <button
        type="button"
        @click="open = ! open"
        aria-haspopup="listbox"
        :aria-expanded="open.toString()"
        @if($label)
            aria-label="{{ $label }}"
            title="{{ $label }}"
        @endif
        x-bind:class="open
            ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200'
            : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200'"
        {{ $attributes->merge(['class' => 'ui-icon-toggle-dropdown inline-flex h-9 shrink-0 cursor-pointer items-center gap-1 px-2 transition focus:outline-none']) }}
    >
        {{ $trigger }}
        <x-icon.chevron-down class="h-3 w-3 shrink-0 text-gray-400 transition-transform dark:text-gray-500" x-bind:class="open ? 'rotate-180' : ''" />
    </button>

    <ul
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        role="listbox"
        class="ui-popover scrollbar-thin absolute top-full z-40 mt-1 {{ $width }} {{ $alignmentClasses }} {{ $maxHeight }} overflow-y-auto py-1"
    >
        {{ $slot }}
    </ul>
</div>
