@props([
    'name' => null,
    'id' => null,
    'options' => [],
    'placeholder' => 'Select options',
    'disabled' => false,
    'error' => false,
    'success' => false,
])

@php
    $stateClass = $error ? 'form-input-error-within' : ($success ? 'form-input-success-within' : 'form-input-default-within');
    $initialOptions = collect($options)->map(fn ($label, $value) => [
        'value' => $value,
        'text' => $label,
        'selected' => false,
    ])->values()->all();
@endphp

<div
    x-data="{
        options: @js($initialOptions),
        selected: [],
        show: false,
        open() { this.show = true; },
        close() { this.show = false; },
        select(index) {
            if (!this.options[index].selected) {
                this.options[index].selected = true;
                this.selected.push(index);
            } else {
                this.selected.splice(this.selected.lastIndexOf(index), 1);
                this.options[index].selected = false;
            }
        },
        remove(index, option) {
            this.options[option].selected = false;
            this.selected.splice(index, 1);
        },
    }"
    class="relative"
>
    <template x-for="optionIndex in selected" :key="optionIndex">
        <input type="hidden" @if($name) name="{{ $name }}" @endif :value="options[optionIndex].value" />
    </template>

    <div
        @click="open()"
        @keydown.enter="open()"
        @keydown.space.prevent="open()"
        @if(!$disabled) tabindex="0" @endif
        class="form-input {{ $stateClass }} flex min-h-11 cursor-text flex-wrap items-center gap-2 !py-1.5 {{ $disabled ? 'pointer-events-none opacity-60' : '' }}"
    >
        <template x-for="(option, index) in selected" :key="index">
            <div class="flex items-center gap-1.5 rounded-full bg-gray-100 py-1 pr-2 pl-2.5 text-sm text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                <span x-text="options[option].text"></span>
                <button type="button" @click.stop="remove(index, option)" @disabled($disabled) class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-200">
                    <x-icon.x-mark class="h-3 w-3" />
                </button>
            </div>
        </template>
        <span x-show="selected.length === 0" class="px-1 text-sm text-gray-400 dark:text-gray-500">{{ $placeholder }}</span>
        <button
            type="button"
            @click="open()"
            @if($id) id="{{ $id }}" @endif
            @disabled($disabled)
            {{ $attributes->merge(['class' => 'ml-auto h-5 w-5 shrink-0 text-gray-500 dark:text-gray-400']) }}
            :class="show ? 'rotate-180' : ''"
        >
            <x-icon.chevron-down />
        </button>
    </div>

    <div
        x-show="show"
        x-transition
        @click.outside="close()"
        class="ui-popover scrollbar-thin absolute top-full left-0 z-40 mt-1 max-h-52 w-full overflow-y-auto py-1"
    >
        <template x-for="(option, index) in options" :key="index">
            <div
                @click="select(index)"
                class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700"
                :class="option.selected ? 'bg-gray-50 font-medium dark:bg-gray-700' : ''"
                x-text="option.text"
            ></div>
        </template>
    </div>
</div>
