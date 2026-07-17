{{--
    Full-screen modal backdrop — semi-transparent + heavily blurred, matching
    laravista's overlay treatment. Include inside a modal's outer wrapper and
    pass the Alpine boolean to show/hide by, plus what a click on it should do.
    Pass an empty string for `close` to make the backdrop non-dismissive.

    @include('layouts.partials.overlay', ['show' => 'showDemoModal'])
    @include('layouts.partials.overlay', ['show' => 'showDemoModal', 'close' => ''])
--}}
@php
    $close = $close ?? "$show = false";
@endphp

<div
    x-show="{{ $show }}"
    x-cloak
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @if($close) @click="{{ $close }}" @endif
    class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
></div>
