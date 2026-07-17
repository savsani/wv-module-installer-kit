{{--
    Compact dropdown filter for a table toolbar, styled to match the
    per-page selector in <x-ui.table.pagination>. Pairs with dataTable()'s
    setFilter(key, value):

        <x-ui.table.filter
            label="Category"
            placeholder="All Categories"
            :options="['Electronics' => 'Electronics', 'Audio' => 'Audio']"
            @change="setFilter('category', $event.target.value)"
        />
--}}
@props(['label' => null, 'options' => [], 'placeholder' => 'All'])

<label class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
    @if($label)
        <span class="hidden sm:inline">{{ $label }}</span>
    @endif

    <span class="relative inline-flex">
        <select {{ $attributes->merge(['class' => 'form-input form-input-default h-9 w-auto appearance-none !py-0 pr-8 pl-3 text-sm']) }}>
            <option value="">{{ $placeholder }}</option>
            @foreach($options as $value => $optionLabel)
                <option value="{{ $value }}">{{ $optionLabel }}</option>
            @endforeach
        </select>
        <span class="pointer-events-none absolute top-1/2 right-2.5 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <x-icon.chevron-down class="h-4 w-4" />
        </span>
    </span>
</label>
