<div x-show="mobileOpen" x-cloak x-transition.opacity class="fixed inset-x-0 top-16 bottom-0 z-40 bg-gray-400/50 backdrop-blur-[32px] lg:hidden" @click="mobileOpen = false"></div>

<aside
    class="fixed left-0 top-16 bottom-0 z-40 flex w-64 -translate-x-full flex-col overflow-hidden border-r border-gray-200 bg-white transition-[width,transform] duration-200 ease-in-out dark:border-gray-800 dark:bg-gray-900 lg:top-0 lg:translate-x-0"
    :class="[mobileOpen ? 'translate-x-0' : '-translate-x-full', expanded ? 'lg:w-64' : 'lg:w-20']"
    @mouseenter="hovering = true"
    @mouseleave="hovering = false"
>
    <div class="hidden h-16 shrink-0 items-center gap-2 overflow-hidden border-b border-gray-200 px-4 lg:flex dark:border-gray-800" :class="!expanded && 'lg:justify-center lg:px-2'">
        <x-branding.logo class="h-8 w-8 shrink-0" x-show="!expanded" x-cloak />
        <x-branding.logo-full
            class="h-8 shrink-0"
            x-show="expanded"
            x-transition:enter="transition ease-out duration-150 delay-75"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        />
    </div>

    <nav class="scrollbar-hidden flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-4">
        @auth
            <p
                class="overflow-hidden px-3 pt-1 pb-1 text-xs font-semibold tracking-wider whitespace-nowrap text-gray-400 uppercase dark:text-gray-500"
                x-show="expanded"
                x-transition:enter="transition ease-out duration-150 delay-75"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                Menu
            </p>

            <x-sidebar.link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <x-slot:icon>
                    <x-icon.home class="h-5 w-5 shrink-0" />
                </x-slot:icon>
                Dashboard
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.*')">
                <x-slot:icon>
                    <x-icon.user-circle class="h-5 w-5 shrink-0" />
                </x-slot:icon>
                Profile
            </x-sidebar.link>

            @php
                $hasAdmin = \Nwidart\Modules\Facades\Module::has('Admin') && \Nwidart\Modules\Facades\Module::isEnabled('Admin');
                $hasActivityLog = \Nwidart\Modules\Facades\Module::has('ActivityLog') && \Nwidart\Modules\Facades\Module::isEnabled('ActivityLog');
            @endphp

            @if ($hasAdmin || $hasActivityLog)
                @hasrole('admin')
                    <p
                        class="overflow-hidden px-3 pt-4 pb-1 text-xs font-semibold tracking-wider whitespace-nowrap text-gray-400 uppercase dark:text-gray-500"
                        x-show="expanded"
                        x-transition:enter="transition ease-out duration-150 delay-75"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                    >
                        Administrator
                    </p>

                    @if ($hasAdmin)
                        @include('admin::partials.nav')
                    @endif

                    @if ($hasActivityLog)
                        @include('activitylog::partials.nav')
                    @endif
                @endhasrole
            @endif
        @endauth

        <p
            class="overflow-hidden px-3 pt-4 pb-1 text-xs font-semibold tracking-wider whitespace-nowrap text-gray-400 uppercase dark:text-gray-500"
            x-show="expanded"
            x-transition:enter="transition ease-out duration-150 delay-75"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            Examples
        </p>

        <x-sidebar.link href="{{ route('examples.form-elements') }}" :active="request()->routeIs('examples.form-elements')">
            <x-slot:icon>
                <x-icon.document-text class="h-5 w-5 shrink-0" />
            </x-slot:icon>
            Form Elements
        </x-sidebar.link>

        <x-sidebar.link href="{{ route('examples.data-table') }}" :active="request()->routeIs('examples.data-table')">
            <x-slot:icon>
                <x-icon.table-cells class="h-5 w-5 shrink-0" />
            </x-slot:icon>
            Data Table
        </x-sidebar.link>

        <x-sidebar.accordion label="UI Elements" :active="request()->routeIs('examples.ui.*')">
            <x-slot:icon>
                <x-icon.layers class="h-5 w-5 shrink-0" />
            </x-slot:icon>

            <x-sidebar.link href="{{ route('examples.ui.alerts') }}" :active="request()->routeIs('examples.ui.alerts')">
                <x-slot:icon>
                    <x-icon.bell class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                Alerts
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.ui.buttons') }}" :active="request()->routeIs('examples.ui.buttons')">
                <x-slot:icon>
                    <x-icon.squares-2x2 class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                Buttons
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.ui.badges') }}" :active="request()->routeIs('examples.ui.badges')">
                <x-slot:icon>
                    <x-icon.tag class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                Badges
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.ui.data-display') }}" :active="request()->routeIs('examples.ui.data-display')">
                <x-slot:icon>
                    <x-icon.squares-2x2 class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                Data Display
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.ui.tabs') }}" :active="request()->routeIs('examples.ui.tabs')">
                <x-slot:icon>
                    <x-icon.browser-window class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                Tabs
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.ui.toasts') }}" :active="request()->routeIs('examples.ui.toasts')">
                <x-slot:icon>
                    <x-icon.chat-bubble-ellipsis class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                Toasts
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.ui.modals') }}" :active="request()->routeIs('examples.ui.modals')">
                <x-slot:icon>
                    <x-icon.chat-bubble class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                Modals
            </x-sidebar.link>

        </x-sidebar.accordion>

        <x-sidebar.accordion label="Errors" :active="request()->routeIs('examples.errors.*')">
            <x-slot:icon>
                <x-icon.exclamation-triangle class="h-5 w-5 shrink-0" />
            </x-slot:icon>

            <x-sidebar.link href="{{ route('examples.errors.403') }}" :active="request()->routeIs('examples.errors.403')">
                <x-slot:icon>
                    <x-icon.lock-closed class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                403 Forbidden
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.errors.404') }}" :active="request()->routeIs('examples.errors.404')">
                <x-slot:icon>
                    <x-icon.question-mark-circle class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                404 Not Found
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.errors.500') }}" :active="request()->routeIs('examples.errors.500')">
                <x-slot:icon>
                    <x-icon.exclamation-circle class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                500 Server Error
            </x-sidebar.link>

            <x-sidebar.link href="{{ route('examples.errors.503') }}" :active="request()->routeIs('examples.errors.503')">
                <x-slot:icon>
                    <x-icon.arrow-path class="h-4 w-4 shrink-0" />
                </x-slot:icon>
                503 Unavailable
            </x-sidebar.link>
        </x-sidebar.accordion>
    </nav>
</aside>
