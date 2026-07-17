{{--
    Reusable modal dialog. The caller owns the Alpine boolean (declare it in an
    ancestor's x-data) and passes its name as `show`; this component only
    renders the overlay + panel for it.

    <div x-data="{ showDemo: false }">
        <x-ui.button @click="showDemo = true">Open</x-ui.button>

        <x-ui.modal show="showDemo" title="Demo modal" description="Some helper text.">
            Body content or a form goes here.

            <x-slot:footer>
                <x-ui.button variant="secondary" style="outline" @click="showDemo = false">Cancel</x-ui.button>
                <x-ui.button variant="primary" @click="showDemo = false">Confirm</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    </div>

    Pass `:close-on-backdrop="false"` to stop backdrop clicks and Escape from
    dismissing it — the close (X) button and any footer buttons still work.
    Pass `:close-button="false"` to hide the top-right (X) button entirely —
    useful for confirm dialogs where Cancel/Confirm footer buttons are the
    only way out.
    Pass `on-close="someStore.cancel()"` when dismissing (backdrop, Escape,
    the X button) needs to run more than `show = false` — e.g. rejecting a
    pending promise. Footer buttons aren't affected; wire their own @click.

    Focus is trapped inside the panel while open (via @alpinejs/focus'
    `x-trap`) and returns to whatever triggered the modal when it closes —
    Tab/Shift+Tab can't escape to the page behind it.
--}}
@props([
    'show',
    'title' => null,
    'description' => null,
    'closeOnBackdrop' => true,
    'closeButton' => true,
    'maxWidth' => 'md',
    'onClose' => null,
])

@php
    $maxWidthClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    $maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['md'];
    $close = $onClose ?? "$show = false";
@endphp

<div
    x-show="{{ $show }}"
    x-cloak
    class="fixed inset-0 z-[var(--z-modal)] flex items-center justify-center p-4"
    aria-modal="true"
    role="dialog"
    @if($closeOnBackdrop) @keydown.escape.window="{{ $close }}" @endif
>
    @include('layouts.partials.overlay', ['show' => $show, 'close' => $closeOnBackdrop ? $close : ''])

    <div
        x-show="{{ $show }}"
        x-trap="{{ $show }}"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        {{ $attributes->merge(['class' => "ui-modal relative w-full {$maxWidthClass} p-6 text-left align-middle"]) }}
    >
        @if($title || $description)
            <div class="flex items-start justify-between gap-4">
                <div>
                    @if($title)
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h2>
                    @endif
                    @if($description)
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                    @endif
                </div>

                @if($closeButton)
                    <button
                        type="button"
                        @click="{{ $close }}"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-pill bg-gray-100 text-gray-400 transition hover:bg-gray-200 hover:text-gray-600 focus:outline-none dark:bg-white/5 dark:hover:bg-white/10 dark:hover:text-gray-300"
                        aria-label="Close"
                    >
                        <x-icon.x-mark class="h-4 w-4" />
                    </button>
                @endif
            </div>
        @elseif($closeButton)
            <button
                type="button"
                @click="{{ $close }}"
                class="absolute right-4 top-4 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition hover:bg-gray-200 hover:text-gray-600 focus:outline-none dark:bg-white/5 dark:hover:bg-white/10 dark:hover:text-gray-300"
                aria-label="Close"
            >
                <x-icon.x-mark class="h-4 w-4" />
            </button>
        @endif

        <div @if($title || $description) class="mt-4" @endif>
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="mt-6 flex justify-end gap-3">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
