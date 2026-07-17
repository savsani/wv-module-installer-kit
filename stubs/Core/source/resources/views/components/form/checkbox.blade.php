@props([
    'id' => null,
    'name' => null,
    'value' => '1',
    'checked' => false,
    'disabled' => false,
])

@php($id = $id ?? 'chk_'.uniqid())

<div x-data="{ checked: {{ $checked ? 'true' : 'false' }} }">
    <label for="{{ $id }}" class="flex items-center text-sm font-medium text-gray-700 select-none dark:text-gray-300 {{ $disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
        <div class="relative">
            <input
                type="checkbox"
                id="{{ $id }}"
                @if($name) name="{{ $name }}" @endif
                value="{{ $value }}"
                @if($checked) checked @endif
                @disabled($disabled)
                @change="checked = !checked"
                {{ $attributes->merge(['class' => 'sr-only']) }}
            />
            <x-form.checkbox-box checked="checked" class="mr-3" />
        </div>
        {{ $slot }}
    </label>
</div>
