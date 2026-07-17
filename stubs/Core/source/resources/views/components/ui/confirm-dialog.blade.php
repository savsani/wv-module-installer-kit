{{--
    Global reusable confirm dialog — replaces window.confirm() with a styled
    modal built on <x-ui.modal>. Mounted once in layouts.app; trigger it from
    anywhere with:

        @click="$store.confirmDialog.open({
            title: 'Delete user?',
            message: 'Are you sure? This action cannot be undone.',
            variant: 'danger', // success | warning | danger | info | primary
            confirmText: 'Delete',
            cancelText: 'Cancel',
            onConfirm: () => { ... },
        })"
--}}
<x-ui.modal show="$store.confirmDialog.show" :close-button="false" max-width="sm">
    <div class="flex items-center gap-3">
        <div
            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-pill"
            :class="{
                'bg-green-50 text-green-500 dark:bg-green-500/15 dark:text-green-400': $store.confirmDialog.variant === 'success',
                'bg-amber-50 text-amber-500 dark:bg-amber-500/15 dark:text-amber-400': $store.confirmDialog.variant === 'warning',
                'bg-red-50 text-red-500 dark:bg-red-500/15 dark:text-red-400': $store.confirmDialog.variant === 'danger',
                'bg-sky-50 text-sky-500 dark:bg-sky-500/15 dark:text-sky-400': $store.confirmDialog.variant === 'info',
                'bg-brand-50 text-brand-500 dark:bg-brand-500/15 dark:text-brand-400': $store.confirmDialog.variant === 'primary',
            }"
        >
            <x-icon.check-circle x-show="$store.confirmDialog.variant === 'success'" class="h-6 w-6" />
            <x-icon.exclamation-triangle x-show="$store.confirmDialog.variant === 'warning'" class="h-6 w-6" />
            <x-icon.x-circle x-show="$store.confirmDialog.variant === 'danger'" class="h-6 w-6" />
            <x-icon.information-circle x-show="$store.confirmDialog.variant === 'info' || $store.confirmDialog.variant === 'primary'" class="h-6 w-6" />
        </div>

        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="$store.confirmDialog.title"></h2>
    </div>

    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400" x-text="$store.confirmDialog.message"></p>

    <x-slot:footer>
        <x-ui.button type="button" variant="secondary" style="outline" @click="$store.confirmDialog.close()" x-text="$store.confirmDialog.cancelText"></x-ui.button>

        <button
            type="button"
            @click="$store.confirmDialog.confirm()"
            class="ui-button inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
            :class="{
                'bg-green-500 hover:bg-green-600 focus:ring-green-500': $store.confirmDialog.variant === 'success',
                'bg-amber-500 hover:bg-amber-600 focus:ring-amber-500': $store.confirmDialog.variant === 'warning',
                'bg-red-500 hover:bg-red-600 focus:ring-red-500': $store.confirmDialog.variant === 'danger',
                'bg-sky-500 hover:bg-sky-600 focus:ring-sky-500': $store.confirmDialog.variant === 'info',
                'bg-brand-500 hover:bg-brand-600 focus:ring-brand-500': $store.confirmDialog.variant === 'primary',
            }"
            x-text="$store.confirmDialog.confirmText"
        ></button>
    </x-slot:footer>
</x-ui.modal>
