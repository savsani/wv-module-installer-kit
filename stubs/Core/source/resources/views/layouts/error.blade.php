<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white dark:bg-gray-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@hasSection('title')@yield('title') - {{ config('app.name') }}@else{{ config('app.name', 'Laravel') }}@endif</title>

        @include('layouts.partials.theme-init')

        @fonts
        @vite(['Modules/Core/resources/css/app.css', 'Modules/Core/resources/js/app.js'])
    </head>
    <body class="flex h-full flex-col bg-white antialiased dark:bg-gray-950">
        @auth
            @include('layouts.partials.impersonation-banner')
        @endauth

        @yield('content')
    </body>
</html>
