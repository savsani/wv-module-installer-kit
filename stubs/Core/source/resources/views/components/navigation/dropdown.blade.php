@props(['align' => 'right', 'width' => '48'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$widthClass = match ((string) $width) {
    '48' => 'w-48',
    '56' => 'w-56',
    '64' => 'w-64',
    default => 'w-48',
};
@endphp

<div
    class="relative"
    x-data="{
        open: false,

        focusItem(target) {
            let items = Array.from(this.$refs.content.querySelectorAll('a, button:not(:disabled)'));
            if (! items.length) return;

            let current = items.indexOf(document.activeElement);
            let index = target === 'first' ? 0
                : target === 'last' ? items.length - 1
                : target === 'next' ? (current + 1) % items.length
                : (current - 1 + items.length) % items.length;

            items[index].focus();
        },
    }"
    @click.outside="open = false"
    @keydown.escape.window="open = false; $refs.trigger.querySelector('a, button')?.focus()"
>
    <div
        x-ref="trigger"
        @click="open = ! open"
        @keydown.arrow-down.prevent="open = true; $nextTick(() => focusItem('first'))"
    >
        {{ $trigger }}
    </div>

    <div x-ref="content"
         x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click="open = false"
         @keydown.arrow-down.prevent="focusItem('next')"
         @keydown.arrow-up.prevent="focusItem('previous')"
         @keydown.home.prevent="focusItem('first')"
         @keydown.end.prevent="focusItem('last')"
         class="ui-popover absolute z-50 mt-2 {{ $alignmentClasses }} {{ $widthClass }} py-1"
         x-cloak>
        {{ $content }}
    </div>
</div>
