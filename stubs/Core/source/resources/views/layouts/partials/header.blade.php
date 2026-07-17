<header
    class="sticky top-0 z-50 bg-white/80 backdrop-blur dark:bg-gray-900/80"
    x-data="{ mobileMenuOpen: false }"
    @keydown.escape.window="mobileMenuOpen = false"
    @click.outside="mobileMenuOpen = false"
>
    <div class="flex h-16 shrink-0 items-center gap-4 border-b border-gray-200 px-4 sm:px-6 lg:px-8 dark:border-gray-800">
        <div class="hidden lg:block">
            <x-ui.icon-button @click="toggle" aria-label="Toggle sidebar">
<x-icon.bars-3 class="h-5 w-5" />
            </x-ui.icon-button>
        </div>

        <div class="grid flex-1 grid-cols-3 items-center lg:hidden">
            <x-ui.icon-button @click="toggle" class="justify-self-start" aria-label="Toggle sidebar">
                <x-icon.bars-3 x-show="!mobileOpen" class="h-5 w-5" />
                <x-icon.x-mark-outline x-show="mobileOpen" x-cloak class="h-5 w-5" />
            </x-ui.icon-button>

            <div class="flex items-center justify-self-center gap-2">
                <x-branding.logo-full class="h-8" />
            </div>

            <x-ui.icon-button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="justify-self-end"
                x-bind:class="mobileMenuOpen ? 'bg-gray-100 dark:bg-gray-800' : ''"
                aria-label="Toggle menu"
                x-bind:aria-expanded="mobileMenuOpen"
            >
<x-icon.dots-horizontal class="h-7 w-7" />
            </x-ui.icon-button>
        </div>

        <div class="hidden flex-1 lg:block"></div>

        <div class="hidden items-center gap-2 lg:flex">
            <x-navigation.theme-toggle />

            <x-navigation.language-switcher />

            @auth
                <x-navigation.user-menu />
            @endauth
        </div>
    </div>

    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="flex items-center justify-between border-b border-gray-200 px-4 py-3 lg:hidden dark:border-gray-800"
    >
        <x-navigation.theme-toggle />

        <x-navigation.language-switcher />

        @auth
            <x-navigation.user-menu />
        @endauth
    </div>
</header>
