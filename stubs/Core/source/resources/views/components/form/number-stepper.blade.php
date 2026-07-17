@props([
    'name' => null,
    'id' => null,
    'value' => 1,
    'min' => 1,
    'max' => 99,
    'disabled' => false,
])

<div
    x-data="{ value: {{ $value }}, min: {{ $min }}, max: {{ $max }},
        dec() { if (this.value > this.min) this.value--; },
        inc() { if (this.value < this.max) this.value++; },
        clamp() { this.value = Math.min(this.max, Math.max(this.min, parseInt(this.value) || this.min)); }
    }"
    class="form-input form-input-default-within flex h-11 w-40 items-center !p-0 {{ $disabled ? 'cursor-not-allowed opacity-60' : '' }}"
>
    <button
        type="button"
        @click="dec()"
        :disabled="{{ $disabled ? 'true' : 'false' }} || value <= min"
        class="flex h-full w-11 shrink-0 items-center justify-center rounded-l-lg border-r border-gray-300 bg-gray-50 text-gray-500 transition hover:bg-gray-100 disabled:opacity-60 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600"
    >
        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 10a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5H4a.75.75 0 0 1-.75-.75Z" fill="currentColor" />
        </svg>
    </button>
    <input
        type="number"
        @if($name) name="{{ $name }}" @endif
        @if($id) id="{{ $id }}" @endif
        x-model="value"
        @change="clamp()"
        :min="min"
        :max="max"
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'h-full w-full border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:ring-0 focus:outline-none disabled:cursor-not-allowed dark:text-gray-100 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none']) }}
    />
    <button
        type="button"
        @click="inc()"
        :disabled="{{ $disabled ? 'true' : 'false' }} || value >= max"
        class="flex h-full w-11 shrink-0 items-center justify-center rounded-r-lg border-l border-gray-300 bg-gray-50 text-gray-500 transition hover:bg-gray-100 disabled:opacity-60 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600"
    >
        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 3.25a.75.75 0 0 1 .75.75v5.25H16a.75.75 0 0 1 0 1.5h-5.25V16a.75.75 0 0 1-1.5 0v-5.25H4a.75.75 0 0 1 0-1.5h5.25V4a.75.75 0 0 1 .75-.75Z" fill="currentColor" />
        </svg>
    </button>
</div>
