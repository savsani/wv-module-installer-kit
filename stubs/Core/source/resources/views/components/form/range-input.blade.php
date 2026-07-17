@props([
    'name' => null,
    'id' => null,
    'value' => 0,
    'min' => 0,
    'max' => 1,
    'step' => 0.01,
    'decimals' => 2,
    'disabled' => false,
])

<div x-data="{ value: {{ $value }}, min: {{ $min }}, max: {{ $max }} }" class="flex items-center gap-3 {{ $disabled ? 'cursor-not-allowed opacity-60' : '' }}">
    <span class="shrink-0 text-xs text-gray-400 dark:text-gray-500">{{ $min }}</span>
    <input
        type="range"
        x-model="value"
        :min="min"
        :max="max"
        step="{{ $step }}"
        @disabled($disabled)
        class="h-1.5 flex-1 cursor-pointer appearance-none rounded-full bg-gray-200 accent-brand-600 disabled:cursor-not-allowed dark:bg-gray-700 dark:accent-brand-500
            [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-brand-600 [&::-webkit-slider-thumb]:shadow-sm dark:[&::-webkit-slider-thumb]:bg-brand-500
            [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:bg-brand-600 dark:[&::-moz-range-thumb]:bg-brand-500"
    />
    <span class="shrink-0 text-xs text-gray-400 dark:text-gray-500">{{ $max }}</span>
    <input
        type="text"
        @if($name) name="{{ $name }}" @endif
        @if($id) id="{{ $id }}" @endif
        x-model="value"
        @change="value = Math.min(max, Math.max(min, parseFloat(value) || 0)).toFixed({{ $decimals }})"
        spellcheck="false"
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'form-input form-input-default h-11 w-20 shrink-0 text-center']) }}
    />
</div>
