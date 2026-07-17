@props([
    'name' => null,
    'id' => null,
    'value' => 40,
    'min' => 0,
    'max' => 100,
    'prefix' => '$',
    'disabled' => false,
])

<div x-data="{ value: {{ $value }}, min: {{ $min }}, max: {{ $max }} }" class="{{ $disabled ? 'cursor-not-allowed opacity-60' : '' }}">
    <input type="hidden" @if($name) name="{{ $name }}" @endif :value="value" />
    <div class="mb-0.5 flex items-center justify-between text-sm text-gray-900 dark:text-gray-100">
        <span x-text="'{{ $prefix }}' + value"></span>
        <span x-text="Math.round((value / max) * 100) + '%'"></span>
    </div>
    <input
        type="range"
        @if($id) id="{{ $id }}" @endif
        x-model="value"
        :min="min"
        :max="max"
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'h-1 w-full cursor-pointer appearance-none rounded-full bg-gray-200 accent-brand-600 disabled:cursor-not-allowed dark:bg-gray-700 dark:accent-brand-500
            [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-brand-600 [&::-webkit-slider-thumb]:shadow-sm dark:[&::-webkit-slider-thumb]:bg-brand-500
            [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:bg-brand-600 dark:[&::-moz-range-thumb]:bg-brand-500']) }}
    />
    <div class="mt-1.5 flex justify-between text-xs text-gray-400 dark:text-gray-500">
        <span x-text="'{{ $prefix }}' + min"></span>
        <span x-text="'{{ $prefix }}' + max"></span>
    </div>
</div>
