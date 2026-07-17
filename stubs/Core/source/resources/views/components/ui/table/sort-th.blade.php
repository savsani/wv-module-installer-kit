{{--
    Sortable <th> for a data table page. Assumes an ancestor element carries
    the dataTable() Alpine component (`sortKey`, `sortDir`, `toggleSort()`).

        <x-ui.table.sort-th sort-key="price">Price</x-ui.table.sort-th>
--}}
@props(['sortKey'])

@php
    $ascActiveExpression = "sortKey === '{$sortKey}' && sortDir === 'asc' ? 'text-brand-600 dark:text-brand-400' : 'text-gray-300 dark:text-gray-600'";
    $descActiveExpression = "sortKey === '{$sortKey}' && sortDir === 'desc' ? 'text-brand-600 dark:text-brand-400' : 'text-gray-300 dark:text-gray-600'";
@endphp

<th scope="col" {{ $attributes->merge(['class' => 'px-4 py-3.5 text-left whitespace-nowrap']) }}>
    <button
        type="button"
        @click="toggleSort('{{ $sortKey }}')"
        class="inline-flex items-center gap-1 text-xs font-medium tracking-wide text-gray-500 uppercase transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
    >
        {{ $slot }}
        <span class="inline-flex shrink-0 flex-col items-center justify-center gap-0.5">
            <span :class="{{ $ascActiveExpression }}"><x-icon.caret-up class="h-1.5 w-2.5" /></span>
            <span :class="{{ $descActiveExpression }}"><x-icon.caret-down class="h-1.5 w-2.5" /></span>
        </span>
    </button>
</th>
