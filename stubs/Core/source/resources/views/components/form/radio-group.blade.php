@props([
    'name' => null,
    'options' => [],
    'selected' => null,
    'disabled' => false,
])

@php($groupId = 'radio_'.uniqid())

<div x-data="{ value: '{{ $selected }}' }" {{ $attributes }}>
    <div class="flex flex-wrap items-center gap-6">
        @foreach($options as $optionValue => $label)
            @php($optionId = $groupId.'_'.$optionValue)
            <label for="{{ $optionId }}" class="flex items-center text-sm font-medium text-gray-700 select-none dark:text-gray-300 {{ $disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                <div class="relative">
                    <input
                        type="radio"
                        id="{{ $optionId }}"
                        @if($name) name="{{ $name }}" @endif
                        value="{{ $optionValue }}"
                        class="sr-only"
                        x-model="value"
                        @disabled($disabled)
                    />
                    <div
                        :class="value === '{{ $optionValue }}' ? 'form-accent-active' : 'form-accent-idle'"
                        class="mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.5px] transition-colors"
                    >
                        <span class="h-2 w-2 rounded-full bg-white" :class="value === '{{ $optionValue }}' ? '' : 'opacity-0'"></span>
                    </div>
                </div>
                {{ $label }}
            </label>
        @endforeach
    </div>
</div>
