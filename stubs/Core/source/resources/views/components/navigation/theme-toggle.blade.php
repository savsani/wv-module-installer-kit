<x-ui.icon-button x-data="themeToggle" @click="toggle" :class="$attributes->get('class')" aria-label="Toggle theme">
    <x-icon.sun x-show="dark" class="h-5 w-5" />
    <x-icon.moon x-show="!dark" x-cloak class="h-5 w-5" />
</x-ui.icon-button>
