@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'error' => false,
    'success' => false,
    'disabled' => false,
])

@php
    $stateClass = $error ? 'form-input-error-within' : ($success ? 'form-input-success-within' : 'form-input-default-within');
    $hour = 12;
    $minute = 0;
    $period = 'AM';

    if ($value) {
        [$h24, $m] = array_map('intval', explode(':', $value));
        $hour = $h24 % 12 === 0 ? 12 : $h24 % 12;
        $minute = $m;
        $period = $h24 < 12 ? 'AM' : 'PM';
    }
@endphp

<div
    class="relative"
    x-data="{
        hour: {{ $hour }},
        minute: {{ $minute }},
        period: '{{ $period }}',
        openSegment: null,
        hours: Array.from({ length: 12 }, (_, i) => i + 1),
        minutes: Array.from({ length: 60 }, (_, i) => i),
        get value24() {
            let h24 = this.hour % 12;
            if (this.period === 'PM') h24 += 12;
            return String(h24).padStart(2, '0') + ':' + String(this.minute).padStart(2, '0');
        },
        toggle(segment) { this.openSegment = this.openSegment === segment ? null : segment; },
        close() { this.openSegment = null; },
        pick(segment, value) { this[segment] = value; this.close(); },
    }"
    @click.outside="close()"
>
    <input type="hidden" @if($name) name="{{ $name }}" @endif :value="value24" />

    <div
        {{ $attributes->merge(['class' => "form-input {$stateClass} flex h-11 items-center gap-1 !py-0 " . ($disabled ? 'cursor-not-allowed opacity-60' : '')]) }}
    >
        <div class="relative">
            <button
                type="button"
                @if($id) id="{{ $id }}" @endif
                @click="toggle('hour')"
                @disabled($disabled)
                class="rounded px-1.5 py-1 text-sm text-gray-900 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700"
                x-text="String(hour).padStart(2, '0')"
            ></button>
            <ul
                x-show="openSegment === 'hour'"
                x-cloak
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="ui-popover scrollbar-thin absolute top-full left-0 z-40 mt-1 max-h-52 w-16 overflow-y-auto py-1"
            >
                <template x-for="h in hours" :key="h">
                    <li
                        @click="pick('hour', h)"
                        :class="hour === h ? 'bg-brand-50 font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700'"
                        class="cursor-pointer px-3 py-1.5 text-center text-sm"
                        x-text="String(h).padStart(2, '0')"
                    ></li>
                </template>
            </ul>
        </div>

        <span class="text-gray-400 dark:text-gray-500">:</span>

        <div class="relative">
            <button
                type="button"
                @click="toggle('minute')"
                @disabled($disabled)
                class="rounded px-1.5 py-1 text-sm text-gray-900 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700"
                x-text="String(minute).padStart(2, '0')"
            ></button>
            <ul
                x-show="openSegment === 'minute'"
                x-cloak
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="ui-popover scrollbar-thin absolute top-full left-0 z-40 mt-1 max-h-52 w-16 overflow-y-auto py-1"
            >
                <template x-for="m in minutes" :key="m">
                    <li
                        @click="pick('minute', m)"
                        :class="minute === m ? 'bg-brand-50 font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700'"
                        class="cursor-pointer px-3 py-1.5 text-center text-sm"
                        x-text="String(m).padStart(2, '0')"
                    ></li>
                </template>
            </ul>
        </div>

        <div class="relative ml-1">
            <button
                type="button"
                @click="toggle('period')"
                @disabled($disabled)
                class="rounded px-1.5 py-1 text-sm text-gray-900 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700"
                x-text="period"
            ></button>
            <ul
                x-show="openSegment === 'period'"
                x-cloak
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="ui-popover absolute top-full left-0 z-40 mt-1 w-14 overflow-hidden py-1"
            >
                <template x-for="p in ['AM', 'PM']" :key="p">
                    <li
                        @click="pick('period', p)"
                        :class="period === p ? 'bg-brand-50 font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700'"
                        class="cursor-pointer px-3 py-1.5 text-center text-sm"
                        x-text="p"
                    ></li>
                </template>
            </ul>
        </div>
    </div>
</div>
