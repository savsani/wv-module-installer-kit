{{--
    Search input for the toolbar of a data table page. Bind it to the
    dataTable() component's `search` property from the caller:

        <x-ui.table.search x-model.debounce.300ms="search" placeholder="Search products..." />
--}}
@props(['placeholder' => 'Search...'])

<div class="relative w-full sm:max-w-xs">
    <span class="pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-gray-400 dark:text-gray-500">
        <x-icon.search class="h-4 w-4" />
    </span>

    <input
        type="text"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-input form-input-default h-11 !pl-10']) }}
    />
</div>
