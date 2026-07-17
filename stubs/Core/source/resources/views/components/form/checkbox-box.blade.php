{{--
    The styled checkbox square + check/indeterminate glyph shared by
    <x-form.checkbox> (label-wrapped, for real forms) and the table
    bulk-select checkboxes (bare, no label). `checked`/`indeterminate` are
    raw Alpine expressions, e.g. :checked="$model".
--}}
@props(['checked', 'indeterminate' => null])

<div
    x-bind:class="(({!! $checked !!}) @if($indeterminate) || ({!! $indeterminate !!}) @endif) ? 'form-accent-active' : 'form-accent-idle'"
    {{ $attributes->merge(['class' => 'flex h-5 w-5 shrink-0 items-center justify-center rounded-control border-[1.5px] transition-colors']) }}
>
    @if($indeterminate)
        <svg x-show="({!! $indeterminate !!}) && !({!! $checked !!})" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" class="h-2.5 w-2.5 text-white">
            <path d="M5 12h14" />
        </svg>
        <x-icon.check x-show="!({!! $indeterminate !!})" x-bind:class="({!! $checked !!}) ? 'text-white' : 'text-white opacity-0'" />
    @else
        <x-icon.check x-bind:class="({!! $checked !!}) ? 'text-white' : 'text-white opacity-0'" />
    @endif
</div>
