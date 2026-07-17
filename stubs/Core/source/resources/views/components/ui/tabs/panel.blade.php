@props(['value'])

<div
    role="tabpanel"
    x-show="tab === '{{ $value }}'"
    x-cloak
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    {{ $attributes }}
>
    {{ $slot }}
</div>
