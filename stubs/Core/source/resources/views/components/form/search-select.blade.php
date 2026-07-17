@props([
    'name' => null,
    'id' => null,
    'options' => [],
    'placeholder' => 'Select option...',
    'selected' => null,
    'disabled' => false,
    'error' => false,
    'success' => false,
])

@php
    $stateClass = $error ? 'form-input-error-within' : ($success ? 'form-input-success-within' : 'form-input-default-within');
@endphp

<div
    class="relative"
    x-data="{
        search: '',
        selected: @js($selected),
        selectedLabel: '{{ collect($options)->firstWhere('value', $selected)['label'] ?? '' }}',
        isOpen: false,
        focusedIndex: -1,
        options: @js($options),
        get filtered() {
            if (!this.search) return this.options;
            const q = this.search.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().includes(q));
        },
        open() {
            this.isOpen = true;
            this.focusedIndex = this.selected
                ? this.options.findIndex(o => o.value === this.selected)
                : -1;
            this.$nextTick(() => this.$refs.searchInput.focus());
        },
        close() { this.isOpen = false; this.search = ''; this.focusedIndex = -1; },
        select(option) { this.selected = option.value; this.selectedLabel = option.label; this.close(); },
        clear(e) { e.stopPropagation(); this.selected = null; this.selectedLabel = ''; },
        onKey(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.focusedIndex = Math.min(this.focusedIndex + 1, this.filtered.length - 1);
                this.$nextTick(() => this.$refs.optionsList?.children[this.focusedIndex]?.scrollIntoView({ block: 'nearest' }));
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.focusedIndex = Math.max(this.focusedIndex - 1, 0);
                this.$nextTick(() => this.$refs.optionsList?.children[this.focusedIndex]?.scrollIntoView({ block: 'nearest' }));
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const opt = this.focusedIndex >= 0 ? this.filtered[this.focusedIndex] : this.filtered[0];
                if (opt) this.select(opt);
            } else if (e.key === 'Escape') {
                this.close();
            }
        }
    }"
    @click.outside="close()"
>
    <input type="hidden" @if($name) name="{{ $name }}" @endif :value="selected ?? ''" />

    <div
        @click="open()"
        @keydown.enter="open()"
        @keydown.space.prevent="open()"
        @if(!$disabled) tabindex="0" @endif
        class="form-input {{ $stateClass }} flex h-11 cursor-pointer items-center !py-0 {{ $disabled ? 'pointer-events-none opacity-60' : '' }}"
    >
        <span x-show="!isOpen && !selected" class="text-sm text-gray-400 select-none dark:text-gray-500">{{ $placeholder }}</span>
        <span x-show="!isOpen && selected" x-text="selectedLabel" class="text-sm text-gray-900 select-none dark:text-gray-100"></span>
        <input
            x-show="isOpen"
            x-ref="searchInput"
            x-model="search"
            @keydown="onKey($event)"
            @input="focusedIndex = filtered.length > 0 ? 0 : -1"
            type="text"
            placeholder="Type to search..."
            class="w-full border-0 bg-transparent p-0 text-sm text-gray-900 outline-none placeholder:text-gray-400 focus:ring-0 dark:text-gray-100 dark:placeholder:text-gray-500"
        />
        <div class="ml-auto flex shrink-0 items-center gap-1 pl-2">
            <button
                x-show="selected && !isOpen"
                x-transition.opacity
                type="button"
                @click="clear($event)"
                @disabled($disabled)
                class="flex h-4 w-4 items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
            >
                <x-icon.x-mark class="h-2.5 w-2.5" />
            </button>
            <button
                type="button"
                @click="open()"
                @if($id) id="{{ $id }}" @endif
                @disabled($disabled)
                {{ $attributes->merge(['class' => 'text-gray-500 transition-transform duration-200 dark:text-gray-400']) }}
                :class="isOpen ? 'rotate-180' : ''"
            >
                <x-icon.chevron-down />
            </button>
        </div>
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
        class="ui-popover absolute top-full right-0 left-0 z-40 mt-1 overflow-hidden"
    >
        <p x-show="filtered.length === 0" class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500">No results for "<span x-text="search" class="font-medium"></span>"</p>

        <ul x-ref="optionsList" class="scrollbar-thin max-h-52 overflow-y-auto py-1">
            <template x-for="(option, index) in filtered" :key="option.value">
                <li
                    @click="select(option)"
                    @mouseenter="focusedIndex = index"
                    :class="{
                        'bg-brand-50 dark:bg-brand-500/10': selected === option.value,
                        'bg-gray-50 dark:bg-gray-700': focusedIndex === index && selected !== option.value
                    }"
                    class="flex cursor-pointer items-center justify-between px-4 py-2.5 text-sm"
                >
                    <span
                        :class="selected === option.value ? 'font-medium text-brand-600 dark:text-brand-400' : 'text-gray-700 dark:text-gray-300'"
                        x-text="option.label"
                    ></span>
                    <x-icon.check x-show="selected === option.value" class="h-3.5 w-3.5 shrink-0 stroke-brand-500 dark:stroke-brand-400" />
                </li>
            </template>
        </ul>
    </div>
</div>
