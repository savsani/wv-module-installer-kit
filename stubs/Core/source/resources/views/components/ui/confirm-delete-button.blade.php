{{--
    Icon-only delete button wired to the shared confirm-dialog store
    ($store.confirmDialog, mounted once in layouts.app). `title`, `message`,
    `confirmText`, and `onConfirm` are raw Alpine/JS expressions (like
    <x-ui.modal>'s `show` prop) so callers can interpolate reactive state:

        Static:
            <x-ui.confirm-delete-button onConfirm="deleteRow(row)" />

        Dynamic (inside an x-data scope with reactive state):
            <x-ui.confirm-delete-button
                title="'Delete &quot;' + viewRow.name + '&quot;?'"
                onConfirm="deleteRow(viewRow); showViewModal = false"
            />
--}}
@props([
    'title' => "'Delete this item?'",
    'message' => "'Are you sure? This action cannot be undone.'",
    'confirmText' => "'Delete'",
    'onConfirm',
])

<x-ui.icon-button
    size="sm"
    :class="'text-gray-400 hover:bg-red-50 hover:text-red-600 dark:text-gray-500 dark:hover:bg-red-500/10 dark:hover:text-red-400 ' . $attributes->get('class', '')"
    aria-label="Delete"
    @click="$store.confirmDialog.open({
        title: {!! $title !!},
        message: {!! $message !!},
        variant: 'danger',
        confirmText: {!! $confirmText !!},
        onConfirm: () => { {!! $onConfirm !!} },
    })"
>
    <x-icon.trash class="h-4 w-4" />
</x-ui.icon-button>
