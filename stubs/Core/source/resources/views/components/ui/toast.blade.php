{{--
    Global toast notification stack. Mounted once in layouts.app.

    Trigger from anywhere with:

        $store.toast.show({ title: 'Saved', message: 'Your changes were saved.', type: 'success' })
        $store.toast.show({ title: 'Heads up', message: 'Watch out.', type: 'warning', duration: 5000 })

    Supported types: success | warning | danger | info | primary
    Default duration: 3000 ms (pass duration: 0 to stay until dismissed)

    Colors come from config('ui.toast') — the single source of truth shared
    with the button/badge/alert components — so a new variant or a retheme
    only needs to happen in config/ui.php.
--}}
@php
    $toastColors = config('ui.toast');

    $containerClass = collect($toastColors)
        ->map(fn ($c, $type) => "'{$c['bg']} {$c['border']}': toast.type === '{$type}'")
        ->implode(",\n                ");

    $iconWrapClass = collect($toastColors)
        ->map(fn ($c, $type) => "'{$c['icon_bg']} {$c['icon_text']}': toast.type === '{$type}'")
        ->implode(",\n                    ");

    $textClass = collect($toastColors)
        ->map(fn ($c, $type) => "'{$c['text']}': toast.type === '{$type}'")
        ->implode(",\n                    ");
@endphp

<div
    x-data="{}"
    class="fixed right-4 z-[var(--z-toast)] flex flex-col gap-2.5"
    style="pointer-events: none; top: 84px;"
>
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="ui-toast flex w-80 gap-3 border px-4 py-3.5"
            :class="{
                'items-start': toast.title && toast.message,
                'items-center': !(toast.title && toast.message),
                {!! $containerClass !!}
            }"
            style="pointer-events: auto;"
        >
            {{-- Icon --}}
            <div
                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-pill"
                :class="{
                    {!! $iconWrapClass !!}
                }"
            >
                <x-icon.check x-show="toast.type === 'success'" class="h-4 w-4" />
                <x-icon.exclamation-triangle x-show="toast.type === 'warning'" class="h-4 w-4" />
                <x-icon.x-mark x-show="toast.type === 'danger'" class="h-3.5 w-3.5" />
                <x-icon.information-circle x-show="toast.type === 'info' || toast.type === 'primary'" class="h-4 w-4" />
            </div>

            {{-- Title + message --}}
            <div class="flex-1 min-w-0">
                <p
                    x-show="toast.title"
                    class="text-sm font-semibold"
                    :class="{ {!! $textClass !!} }"
                    x-text="toast.title"
                ></p>
                <p
                    x-show="toast.message"
                    class="text-sm font-medium"
                    :class="{
                        {!! $textClass !!},
                        'mt-0.5': toast.title && toast.message,
                    }"
                    x-text="toast.message"
                ></p>
            </div>

            {{-- Dismiss --}}
            <button
                @click="$store.toast.dismiss(toast.id)"
                class="ml-1 shrink-0 text-gray-400 transition hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                aria-label="Dismiss notification"
            >
                <x-icon.x-mark class="h-3.5 w-3.5" />
            </button>
        </div>
    </template>
</div>
