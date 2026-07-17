{{--
    Bar that appears above a table when one or more rows are selected. `show`
    and `count` are raw Alpine expressions (like <x-ui.modal>'s `show` prop):

        <x-ui.table.bulk-actions-bar show="selectedIds.length > 0" count="selectedIds.length" label="products selected">
            <x-ui.button size="sm" variant="danger" @click="confirmBulkDelete()">Delete</x-ui.button>
            <x-ui.button size="sm" variant="secondary" style="outline" @click="clearSelection()">Clear</x-ui.button>
        </x-ui.table.bulk-actions-bar>
--}}
@props(['show', 'count', 'label' => 'selected'])

<div
    x-show="{{ $show }}"
    x-cloak
    x-transition
    {{ $attributes->merge(['class' => 'ui-bulk-actions-bar flex flex-wrap items-center justify-between gap-3 border border-brand-200 bg-brand-50 px-4 py-3 dark:border-brand-500/30 dark:bg-brand-500/10']) }}
>
    <p class="text-sm font-medium text-brand-700 dark:text-brand-300">
        <span x-text="{{ $count }}"></span> {{ $label }}
    </p>

    <div class="flex flex-wrap items-center gap-2">
        {{ $slot }}
    </div>
</div>
