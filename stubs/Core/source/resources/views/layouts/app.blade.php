<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title')@yield('title') - {{ config('app.name') }}@else{{ config('app.name', 'Laravel') }}@endif</title>

        @include('layouts.partials.theme-init')

        @fonts
        @vite(['Modules/Core/resources/css/app.css', 'Modules/Core/resources/js/app.js'])
    </head>
    <body
        class="h-full bg-gray-50 antialiased dark:bg-gray-950"
        x-data="{
            mobileOpen: false,
            collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
            hovering: false,

            get expanded() {
                return !this.collapsed || this.hovering;
            },

            toggle() {
                if (window.matchMedia('(min-width: 1024px)').matches) {
                    this.collapsed = !this.collapsed;
                    localStorage.setItem('sidebar-collapsed', this.collapsed ? 'true' : 'false');
                } else {
                    this.mobileOpen = !this.mobileOpen;
                }
            },
        }"
    >
        <div class="flex h-full">
            @include('layouts.partials.sidebar')

            <div class="flex min-w-0 flex-1 flex-col transition-[padding] duration-200 ease-in-out" :class="expanded ? 'lg:pl-64' : 'lg:pl-20'">
                @auth
                    @include('layouts.partials.impersonation-banner')
                @endauth

                @include('layouts.partials.header')

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @hasSection('header')
                        <div class="mb-6">
                            @yield('header')
                        </div>
                    @endif

                    @yield('content')
                </main>

                @include('layouts.partials.footer')
            </div>
        </div>

        <x-ui.confirm-dialog />
        <x-ui.toast />
    </body>
</html>
