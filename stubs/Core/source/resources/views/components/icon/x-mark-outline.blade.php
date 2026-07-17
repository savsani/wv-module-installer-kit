{{--
    24x24 stroke-weight X — pairs visually with <x-icon.bars-3> for toggle
    buttons that crossfade between the two. The default <x-icon.x-mark> is a
    smaller filled glyph used for close/dismiss buttons (modal, alert, toast);
    keep using that one there.
--}}
@props(['class' => 'h-5 w-5'])

<svg class="{{ $class }}" {{ $attributes }} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
