{{--
    Language switcher — same trigger/dropdown structure as
    <x-navigation.user-menu>, built on <x-navigation.dropdown>. Visual only
    for now: it persists the picked language to localStorage (mirroring
    themeToggle) but doesn't drive real translations yet.
--}}
@php
    $languages = [
        'en' => ['name' => 'English', 'flag' => 'icon.flag.us'],
        'fr' => ['name' => 'French', 'flag' => 'icon.flag.fr'],
        'es' => ['name' => 'Spanish', 'flag' => 'icon.flag.es'],
        'de' => ['name' => 'German', 'flag' => 'icon.flag.de'],
    ];
@endphp

<div x-data="languageSwitcher">
    <x-navigation.dropdown align="right" width="48">
        <x-slot name="trigger">
            <button
                type="button"
                class="flex cursor-pointer items-center gap-2 rounded-full border border-gray-200 py-1.5 pl-1.5 pr-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 focus:outline-none dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                aria-haspopup="true"
                :aria-expanded="open"
            >
                @foreach($languages as $code => $language)
                    <x-dynamic-component :component="$language['flag']" class="h-6 w-6" x-show="current === '{{ $code }}'" x-cloak />
                @endforeach
                <span x-text="current.toUpperCase()"></span>
                <x-icon.chevron-down class="h-4 w-4 text-gray-400 transition-transform duration-150" x-bind:class="open && 'rotate-180'" />
            </button>
        </x-slot>

        <x-slot name="content">
            @foreach($languages as $code => $language)
                <button
                    type="button"
                    @click="select('{{ $code }}')"
                    class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-100 focus:outline-none dark:text-gray-200 dark:hover:bg-gray-700"
                    :class="current === '{{ $code }}' ? 'bg-gray-100 dark:bg-gray-700' : ''"
                >
                    <x-dynamic-component :component="$language['flag']" class="h-6 w-6" />
                    {{ $language['name'] }}
                </button>
            @endforeach
        </x-slot>
    </x-navigation.dropdown>
</div>
