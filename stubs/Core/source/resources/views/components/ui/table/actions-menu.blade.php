{{--
    Row "⋮" actions dropdown for data table pages. Renders as a fixed-position
    popover teleported to <body> so it is never clipped by the table's
    overflow-x-auto wrapper, and it flips above the trigger automatically when
    there isn't room below (e.g. the last row) — no scrolling required to see it.

    Compose it with <x-ui.table.actions-menu-item>:

        <x-ui.table.actions-menu>
            <x-ui.table.actions-menu-item @click="openViewModal(product)">
                <x-slot:icon><x-icon.eye class="h-4 w-4" /></x-slot:icon>
                View
            </x-ui.table.actions-menu-item>
            <x-ui.table.actions-menu-item @click="openEditModal(product)">
                <x-slot:icon><x-icon.pencil class="h-4 w-4" /></x-slot:icon>
                Edit
            </x-ui.table.actions-menu-item>
            <x-ui.table.actions-menu-item variant="danger" @click="confirmDelete(product)">
                <x-slot:icon><x-icon.trash class="h-4 w-4" /></x-slot:icon>
                Delete
            </x-ui.table.actions-menu-item>
        </x-ui.table.actions-menu>
--}}
<div x-data="tableActionsMenu()" class="inline-flex">
    <x-ui.icon-button
        size="sm"
        @click="toggle($event)"
        x-bind:class="open ? 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300' : ''"
        aria-label="Row actions"
        x-bind:aria-expanded="open"
    >
        <x-icon.dots-vertical class="h-5 w-5" />
    </x-ui.icon-button>

    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            x-ref="menu"
            @click.outside="close()"
            @keydown.escape.window="close()"
            @scroll.window="close()"
            @resize.window="close()"
            @click="close()"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="ui-popover fixed z-50 w-44 py-1"
            :style="`top: ${coords.top}px; left: ${coords.left}px;`"
        >
            {{ $slot }}
        </div>
    </template>
</div>
