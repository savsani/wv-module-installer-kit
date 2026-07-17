{{--
    Bottom bar for a data table page: "Show N per page" + "Showing X-Y of Z"
    on the left, prev/page-numbers/next on the right. Assumes an ancestor
    carries the dataTable() Alpine component (perPage, perPageOptions, page,
    totalPages, pageNumbers, rangeStart, rangeEnd, totalCount, setPerPage(),
    goToPage(), prevPage(), nextPage()).

    `item` names what's being paginated, e.g. "products" — used in "Showing
    1-10 of 30 products".
--}}
@props(['item' => 'items'])

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
        <span class="flex items-center gap-2">
            Show
            <span class="relative inline-flex">
                <select
                    @change="setPerPage($event.target.value)"
                    class="form-input form-input-default h-9 w-auto appearance-none !py-0 pr-8 pl-3 text-sm"
                >
                    <template x-for="option in perPageOptions" :key="option">
                        <option :value="option" :selected="perPage === option" x-text="option === 'all' ? 'All' : option"></option>
                    </template>
                </select>
                <span class="pointer-events-none absolute top-1/2 right-2.5 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <x-icon.chevron-down class="h-4 w-4" />
                </span>
            </span>
            per page
        </span>

        <span class="hidden text-gray-300 sm:inline dark:text-gray-700">|</span>

        <span>
            Showing <span class="font-medium text-gray-700 dark:text-gray-300" x-text="rangeStart"></span>–<span class="font-medium text-gray-700 dark:text-gray-300" x-text="rangeEnd"></span>
            of <span class="font-medium text-gray-700 dark:text-gray-300" x-text="totalCount"></span> {{ $item }}
        </span>
    </div>

    <div class="flex items-center gap-1.5" x-show="totalPages > 1">
        <button
            type="button"
            @click="prevPage()"
            :disabled="page === 1"
            class="ui-pagination inline-flex items-center gap-1 border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5"
        >
            <x-icon.arrow-right class="h-3.5 w-3.5 rotate-180" />
            Prev
        </button>

        <template x-for="(pageItem, index) in pageNumbers" :key="index">
            <button
                type="button"
                @click="pageItem !== '…' && goToPage(pageItem)"
                x-text="pageItem"
                :disabled="pageItem === '…'"
                class="ui-pagination inline-flex h-9 min-w-9 items-center justify-center px-2 text-sm font-medium transition disabled:cursor-default"
                :class="pageItem === '…'
                    ? 'text-gray-400 dark:text-gray-600'
                    : (page === pageItem ? 'bg-brand-600 text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5')"
            ></button>
        </template>

        <button
            type="button"
            @click="nextPage()"
            :disabled="page === totalPages"
            class="ui-pagination inline-flex items-center gap-1 border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5"
        >
            Next
            <x-icon.arrow-right class="h-3.5 w-3.5" />
        </button>
    </div>
</div>
