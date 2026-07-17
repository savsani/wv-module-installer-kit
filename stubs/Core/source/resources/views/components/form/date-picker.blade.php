@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'format' => 'DD/MM/YYYY',
    'placeholder' => null,
    'min' => null,
    'max' => null,
    'disablePast' => false,
    'disableFuture' => false,
    'disabledDates' => [],
    'disabledDaysOfWeek' => [],
    'firstDayOfWeek' => 0,
    'monthNames' => null,
    'monthNamesShort' => null,
    'dayNamesShort' => null,
    'todayLabel' => 'Today',
    'clearLabel' => 'Clear',
    'clearable' => true,
    'closeOnSelect' => true,
    'disabled' => false,
    'error' => false,
    'success' => false,
])

@php
    $toIso = fn ($date) => $date instanceof \DateTimeInterface ? $date->format('Y-m-d') : $date;

    $value = $toIso($value);
    $min = $toIso($min);
    $max = $toIso($max);
    $disabledDates = collect($disabledDates)->map($toIso)->all();

    $monthNames ??= ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $monthNamesShort ??= array_map(fn ($month) => \Illuminate\Support\Str::substr($month, 0, 3), $monthNames);
    $dayNamesShort ??= ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];

    $id ??= 'date_'.uniqid();
    $stateClass = $error ? 'form-input-error-within' : ($success ? 'form-input-success-within' : 'form-input-default-within');
@endphp

<div
    class="relative"
    x-data="datePicker(@js($value), {
        format: @js($format),
        minDate: @js($min),
        maxDate: @js($max),
        disablePast: @js($disablePast),
        disableFuture: @js($disableFuture),
        disabledDates: @js($disabledDates),
        disabledDaysOfWeek: @js($disabledDaysOfWeek),
        firstDayOfWeek: @js($firstDayOfWeek),
        monthNames: @js($monthNames),
        monthNamesShort: @js($monthNamesShort),
        dayNamesShort: @js($dayNamesShort),
        closeOnSelect: @js($closeOnSelect),
    })"
    @click.outside="close()"
>
    <input type="hidden" @if($name) name="{{ $name }}" @endif :value="value ?? ''" />

    <div
        x-ref="trigger"
        @click="{{ $disabled ? '' : 'toggle()' }}"
        @keydown.enter.prevent="{{ $disabled ? '' : 'toggle()' }}"
        @keydown.space.prevent="{{ $disabled ? '' : 'toggle()' }}"
        @if(!$disabled) tabindex="0" @endif
        role="combobox"
        aria-haspopup="dialog"
        :aria-expanded="isOpen.toString()"
        {{ $attributes->merge(['class' => "form-input {$stateClass} flex h-11 items-center gap-2 " . ($disabled ? 'pointer-events-none opacity-60' : 'cursor-pointer')]) }}
    >
        <button type="button" id="{{ $id }}" tabindex="-1" @click.stop="{{ $disabled ? '' : 'toggle()' }}" @disabled($disabled) aria-label="Open calendar" class="shrink-0 text-gray-400 dark:text-gray-500">
            <x-icon.calendar class="h-4 w-4" />
        </button>
        <span x-show="!value" class="text-sm text-gray-400 select-none dark:text-gray-500">{{ $placeholder ?? 'Select date...' }}</span>
        <span x-show="value" x-text="displayValue" class="text-sm text-gray-900 select-none dark:text-gray-100"></span>

        @if($clearable)
            <button
                type="button"
                x-show="value"
                x-transition.opacity
                @click.stop="clear()"
                class="ml-auto flex h-4 w-4 shrink-0 items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
            >
                <x-icon.x-mark class="h-2.5 w-2.5" />
            </button>
        @endif
    </div>

    <div
        x-show="isOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        @keydown="onKeydown($event)"
        role="dialog"
        aria-label="Choose date"
        class="ui-popover absolute top-full left-0 z-40 mt-1 w-72 p-3"
    >
        <div class="mb-2 flex items-center justify-between">
            <button
                type="button"
                @click="prev()"
                class="flex h-7 w-7 items-center justify-center rounded text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                aria-label="Previous"
            >
                <x-icon.chevron-left class="h-4 w-4" />
            </button>

            <button
                type="button"
                @click="cycleView()"
                x-text="headerLabel"
                class="rounded px-2 py-1 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700"
            ></button>

            <button
                type="button"
                @click="next()"
                class="flex h-7 w-7 items-center justify-center rounded text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                aria-label="Next"
            >
                <x-icon.chevron-right class="h-4 w-4" />
            </button>
        </div>

        <template x-if="view === 'days'">
            <div>
                <div class="mb-1 grid grid-cols-7 gap-0.5">
                    <template x-for="d in weekdayLabels" :key="d">
                        <span class="flex h-7 items-center justify-center text-xs font-medium text-gray-400 dark:text-gray-500" x-text="d"></span>
                    </template>
                </div>
                <div class="grid grid-cols-7 gap-0.5" x-ref="dayGrid">
                    <template x-for="cell in days" :key="cell.iso">
                        <button
                            type="button"
                            @click="selectDay(cell)"
                            :disabled="cell.isDisabled"
                            :data-iso="cell.iso"
                            :tabindex="cell.iso === focusedDate ? 0 : -1"
                            :aria-selected="cell.isSelected.toString()"
                            :class="{
                                'bg-brand-600 font-medium text-white hover:bg-brand-600 dark:bg-brand-500': cell.isSelected,
                                'text-gray-700 opacity-10 cursor-not-allowed dark:text-gray-300': cell.isDisabled && !cell.isSelected,
                                'text-gray-700 opacity-40 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700': !cell.isDisabled && !cell.inCurrentMonth && !cell.isSelected,
                                'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700': !cell.isDisabled && cell.inCurrentMonth && !cell.isSelected,
                                'ring-1 ring-inset ring-brand-400 dark:ring-brand-500': cell.isToday && !cell.isSelected,
                            }"
                            class="flex h-8 w-8 items-center justify-center rounded text-sm transition outline-none"
                            x-text="cell.day"
                        ></button>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="view === 'months'">
            <div class="grid grid-cols-3 gap-1">
                <template x-for="m in months" :key="m.index">
                    <button
                        type="button"
                        @click="selectMonth(m.index)"
                        :disabled="m.isDisabled"
                        :class="{
                            'bg-brand-600 font-medium text-white': m.isSelected,
                            'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700': !m.isSelected && !m.isDisabled,
                            'text-gray-700 opacity-10 cursor-not-allowed dark:text-gray-300': m.isDisabled,
                        }"
                        class="rounded px-2 py-2.5 text-sm transition"
                        x-text="m.label"
                    ></button>
                </template>
            </div>
        </template>

        <template x-if="view === 'years'">
            <div class="grid grid-cols-3 gap-1">
                <template x-for="y in years" :key="y.value">
                    <button
                        type="button"
                        @click="selectYear(y.value)"
                        :disabled="y.isDisabled"
                        :class="{
                            'bg-brand-600 font-medium text-white': y.isSelected,
                            'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700': !y.isSelected && !y.isDisabled,
                            'text-gray-700 opacity-10 cursor-not-allowed dark:text-gray-300': y.isDisabled,
                        }"
                        class="rounded px-2 py-2.5 text-sm transition"
                        x-text="y.value"
                    ></button>
                </template>
            </div>
        </template>

        <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-2.5 dark:border-gray-700">
            <button type="button" @click="goToday()" class="text-xs font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                {{ $todayLabel }}
            </button>

            @if($clearable)
                <button type="button" x-show="value" @click="clear()" class="text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    {{ $clearLabel }}
                </button>
            @endif
        </div>
    </div>
</div>
