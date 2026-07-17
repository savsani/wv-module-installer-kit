{{--
    Inline single-select cluster of icon buttons (text align, etc.) — same
    `selected` sharing mechanic as icon-toggle-dropdown, minus the popup:
    all options are visible directly in the toolbar. Children are
    <x-ui.richtext.icon-toggle-radio-group.item>.

    Pass `sync` (an Alpine expression read into `selected` via x-effect,
    e.g. "align") to mirror a real editor's state — pair with `command` on
    each item to write back to it.
--}}
@props(['default' => null, 'sync' => null])

<div class="inline-flex items-center gap-0.5" x-data="{ selected: @js($default) }" @if($sync) x-effect="selected = ({!! $sync !!})" @endif>
    {{ $slot }}
</div>
