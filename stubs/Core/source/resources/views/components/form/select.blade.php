@props([
    'name' => null,
    'id' => null,
    'options' => [],
    'placeholder' => 'Select an option',
    'selected' => null,
    'showPlaceholder' => true,
    'error' => false,
    'success' => false,
])

@php
    $stateClass = $error ? 'form-input-error' : ($success ? 'form-input-success' : 'form-input-default');
@endphp

<div x-data="{ hasValue: {{ $selected ? 'true' : 'false' }} }" class="relative">
    <select
        @if($name) name="{{ $name }}" @endif
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->merge(['class' => "form-input {$stateClass} h-11 appearance-none !pr-11"]) }}
        :class="hasValue ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'"
        @change="hasValue = true"
    >
        @if($showPlaceholder)
            <option value="" class="text-gray-500 dark:bg-gray-800">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $label)
            <option
                value="{{ $value }}"
                class="text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                @selected((string) $selected === (string) $value)
            >{{ $label }}</option>
        @endforeach
    </select>
    <span class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
        <x-icon.chevron-down />
    </span>
</div>
