<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-50 dark:bg-gray-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title')@yield('title') - {{ config('app.name') }}@else{{ config('app.name', 'Laravel') }}@endif</title>

        @include('layouts.partials.theme-init')

        @fonts
        @vite(['Modules/Core/resources/css/app.css', 'Modules/Core/resources/js/app.js'])
    </head>
    <body class="overscroll-none antialiased bg-gray-50 dark:bg-gray-950 scheme-light dark:scheme-dark">
        @auth
            @include('layouts.partials.impersonation-banner')
        @endauth

        <div class="relative flex min-h-screen min-h-dvh flex-col items-center justify-center px-4 py-12">
            <div class="absolute right-4 top-4 flex items-center gap-2">
                <x-navigation.theme-toggle />
                <x-navigation.language-switcher />
            </div>

            <a href="{{ url('/') }}" class="mb-8 flex items-center">
                <x-branding.logo-full class="h-9" />
            </a>

            <div class="ui-card w-full max-w-md p-8">
                @yield('content')
            </div>
        </div>
    </body>
</html>
