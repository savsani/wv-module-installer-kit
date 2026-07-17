{{--
    "Custom color" expandable row appended to the bottom of the Text/
    Highlight color dropdowns (see rich-text-toolbar.blade.php) — reuses the
    same `colorPicker()` Alpine factory and `.color-picker-*` CSS as the
    standalone <x-form.color-picker> (resources/js/color-picker.js), just
    without its own swatch-in-input trigger or floating popover, since it
    already lives inside one (the parent icon-toggle-dropdown).

    `colorPicker()` dispatches a `change` event on every drag/type, not just
    on release/confirm, so `apply` (run with `$event.detail.value` in scope)
    applies the color live as the user adjusts it — matching the instant-
    apply feel of the preset swatches above it. Falls back to updating the
    dropdown's own `selected` when not bound to a real editor (demo mode).

    `apply` calls into the real editor's setColor()/setHighlight(), which
    ends with editor.chain().focus()...run() — that focus() call blurs
    whichever picker input currently has focus (the hue slider, the hex
    field), and a plain <input>'s native "change" event (distinct from
    colorPicker's own CustomEvent('change', { detail })) then bubbles right
    back up into this same listener with no `.detail`, throwing. `@change.stop`
    below on each input keeps their native change events from ever reaching
    here; the `$event.detail &&` guard is a second line of defense in case
    anything else ever bubbles a plain "change" up to this element.

    The Apply button/Enter key dispatch `$dispatch('close-dropdown')` rather
    than setting `open = false` directly — this `<li>` has its own nested
    x-data (colorPicker's), and while Alpine does resolve a plain `open`
    write up to the ancestor icon-toggle-dropdown's scope, routing it
    through an explicit event that the ancestor listens for itself
    (`@close-dropdown="open = false"`, see icon-toggle-dropdown.blade.php)
    is unambiguous regardless of scope nesting.
--}}
@props(['apply' => null])

<li
    class="border-t border-gray-100 p-2 dark:border-gray-800"
    x-data="colorPicker(null, { format: 'hex' })"
    @change="if ($event.detail) { {!! $apply ?? 'selected = $event.detail.value' !!} }"
>
    <button
        type="button"
        @click="toggle()"
        class="flex w-full items-center justify-between rounded px-1 py-1 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5"
    >
        <span class="flex items-center gap-1.5">
            <span class="color-picker-swatch h-3.5 w-3.5 shrink-0 rounded-full" :style="previewStyle"></span>
            Custom color
        </span>
        <x-icon.chevron-down class="h-3 w-3 shrink-0 text-gray-400 transition-transform dark:text-gray-500" x-bind:class="isOpen ? 'rotate-180' : ''" />
    </button>

    <div x-show="isOpen" x-cloak class="mt-2 space-y-2">
        <div
            class="color-picker-area"
            :style="areaStyle"
            @pointerdown="startAreaDrag($event, $el)"
            @pointermove="onAreaDrag($event, $el)"
            @pointerup="endAreaDrag()"
            @pointercancel="endAreaDrag()"
        >
            <div
                tabindex="0"
                role="slider"
                aria-label="Saturation and brightness"
                :aria-valuetext="`Saturation ${s}%, Brightness ${v}%`"
                class="color-picker-marker"
                :style="markerStyle"
                @keydown="onAreaKeydown($event)"
            ></div>
        </div>

        <input
            type="range"
            min="0"
            max="360"
            step="1"
            :value="h"
            @input="h = +$event.target.value; syncText()"
            @change.stop=""
            class="color-picker-hue-slider"
            aria-label="Hue"
        />

        <div class="flex items-center gap-1.5">
            <input
                type="text"
                :value="text"
                @input="onTextInput($event)"
                @change.stop=""
                @keydown.enter.prevent="applyText(); $dispatch('close-dropdown')"
                @blur="applyText()"
                spellcheck="false"
                autocomplete="off"
                class="form-input form-input-default h-8 min-w-0 flex-1 !py-0 font-mono text-xs"
            />
            <button
                type="button"
                @click="applyText(); $dispatch('close-dropdown')"
                aria-label="Apply custom color"
                title="Apply"
                class="ui-icon-button inline-flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
            >
                <x-icon.check class="h-3.5 w-3.5" />
            </button>
        </div>
    </div>
</li>
