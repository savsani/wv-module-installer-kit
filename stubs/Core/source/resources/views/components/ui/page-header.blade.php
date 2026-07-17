@props(['title'])

<h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
@if ($slot->isNotEmpty())
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $slot }}</p>
@endif
