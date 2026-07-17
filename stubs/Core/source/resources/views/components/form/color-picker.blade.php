@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'type' => 'hex', // 'hex' | 'rgba'
    'error' => false,
    'success' => false,
    'disabled' => false,
])

@php
    $value ??= $type === 'rgba' ? 'rgba(79, 70, 229, 1)' : '#4f46e5';
    $id ??= 'color_'.uniqid();
    $stateClass = $error ? 'form-input-error' : ($success ? 'form-input-success' : 'form-input-default');
@endphp

<div
    class="relative"
    x-data="colorPicker(@js($value), { format: @js($type) })"
    @click.outside="close()"
>
    <button
        type="button"
        @click="{{ $disabled ? '' : 'toggle()' }}"
        @disabled($disabled)
        :style="previewStyle"
        aria-label="Pick a color"
        aria-haspopup="dialog"
        :aria-expanded="isOpen.toString()"
        class="color-picker-swatch absolute top-1/2 left-2 z-10 h-7 w-7 -translate-y-1/2 rounded-control {{ $disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}"
    ></button>

    <input
        type="text"
        @if($name) name="{{ $name }}" @endif
        id="{{ $id }}"
        :value="text"
        @input="onTextInput($event)"
        @click="{{ $disabled ? '' : 'open()' }}"
        @keydown.enter.prevent="applyText()"
        @keydown.escape="close()"
        @blur="applyText()"
        spellcheck="false"
        autocomplete="off"
        placeholder="{{ $value }}"
        @disabled($disabled)
        {{ $attributes->merge(['class' => "form-input {$stateClass} h-11 pl-12 font-mono"]) }}
    />

    <div
        x-show="isOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        role="dialog"
        aria-label="Choose color"
        class="ui-popover absolute top-full left-0 z-40 mt-1 w-56 space-y-3 p-3"
    >
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
            class="color-picker-hue-slider"
            aria-label="Hue"
        />

        @if($type === 'rgba')
            <input
                type="range"
                min="0"
                max="100"
                step="1"
                :value="Math.round(a * 100)"
                @input="a = (+$event.target.value) / 100; syncText()"
                :style="alphaTrackStyle"
                class="color-picker-alpha-slider"
                aria-label="Alpha"
            />
        @endif
    </div>
</div>
