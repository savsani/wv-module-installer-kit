@props([
    'id' => null,
    'name' => null,
    'value' => '1',
    'checked' => false,
    'disabled' => false,
])

@php($id = $id ?? 'tgl_'.uniqid())

<div x-data="{ on: {{ $checked ? 'true' : 'false' }} }">
    <label for="{{ $id }}" class="flex items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-300 {{ $disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
        <div class="relative">
            <input
                type="checkbox"
                id="{{ $id }}"
                @if($name) name="{{ $name }}" @endif
                value="{{ $value }}"
                @if($checked) checked @endif
                @disabled($disabled)
                @change="on = !on"
                {{ $attributes->merge(['class' => 'sr-only']) }}
            />
            <div class="block h-6 w-11 rounded-full transition-colors" :class="on ? 'bg-brand-600 dark:bg-brand-500' : 'bg-gray-200 dark:bg-gray-700'"></div>
            <div class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow-sm duration-300 ease-linear" :class="on ? 'translate-x-full' : 'translate-x-0'"></div>
        </div>
        {{ $slot }}
    </label>
</div>
