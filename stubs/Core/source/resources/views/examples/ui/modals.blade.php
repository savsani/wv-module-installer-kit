@extends('layouts.app')

@section('title', 'Modals')

@section('header')
    <x-ui.page-header title="Modals">
        Reference gallery for <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.modal&gt;</code>.
        The backdrop uses laravista's blurred overlay; pass <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">:close-on-backdrop="false"</code>
        to stop backdrop clicks and Escape from dismissing it.
    </x-ui.page-header>
@endsection

@section('content')
    <div class="grid gap-6 sm:grid-cols-2">
        <x-ui.card-section
            x-data="{ showTextModal: false }"
            title="Backdrop dismissible"
            description="Default behavior — clicking the backdrop or pressing Escape closes the modal."
        >
            <x-ui.button variant="primary" @click="showTextModal = true">Open modal</x-ui.button>

            <x-ui.modal show="showTextModal" title="Demo modal" description="This is placeholder text showing a simple modal with a title and description.">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Because <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">closeOnBackdrop</code> is left at its default,
                    clicking anywhere on the blurred overlay — or pressing Escape — dismisses this modal.
                </p>

                <x-slot:footer>
                    <x-ui.button type="button" variant="secondary" style="outline" @click="showTextModal = false">Cancel</x-ui.button>
                    <x-ui.button type="button" variant="primary" @click="showTextModal = false">Got it</x-ui.button>
                </x-slot:footer>
            </x-ui.modal>
        </x-ui.card-section>

        <x-ui.card-section
            x-data="{ showFormModal: false }"
            title="Backdrop not dismissible"
            description="A form modal — the backdrop and Escape are disabled, so only the × button or the footer buttons close it."
        >
            <x-ui.button variant="primary" @click="showFormModal = true">Open modal</x-ui.button>

            <x-ui.modal show="showFormModal" title="Add Product" description="Fill in the details to add a new product." :close-on-backdrop="false" max-width="lg">
                <form class="space-y-5" onsubmit="return false">
                    <x-form.field label="Product Name" for="product_name">
                        <x-form.input id="product_name" name="product_name" placeholder='e.g. MacBook Pro 16"' />
                    </x-form.field>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.field label="SKU" for="product_sku">
                            <x-form.input id="product_sku" name="product_sku" placeholder="e.g. ELEC-001" />
                        </x-form.field>

                        <x-form.field label="Category" for="product_category">
                            <x-form.select
                                id="product_category"
                                name="product_category"
                                :options="['electronics' => 'Electronics', 'apparel' => 'Apparel', 'home' => 'Home & Garden', 'toys' => 'Toys']"
                                selected="electronics"
                            />
                        </x-form.field>

                        <x-form.field label="Price ($)" for="product_price">
                            <x-form.input id="product_price" name="product_price" type="number" placeholder="0.00" />
                        </x-form.field>

                        <x-form.field label="Stock" for="product_stock">
                            <x-form.input id="product_stock" name="product_stock" type="number" value="0" />
                        </x-form.field>
                    </div>

                    <x-form.field label="Status" for="product_status">
                        <x-form.select
                            id="product_status"
                            name="product_status"
                            :options="['in_stock' => 'In Stock', 'low_stock' => 'Low Stock', 'out_of_stock' => 'Out of Stock']"
                            selected="in_stock"
                        />
                    </x-form.field>
                </form>

                <x-slot:footer>
                    <x-ui.button type="button" variant="secondary" style="outline" @click="showFormModal = false">Cancel</x-ui.button>
                    <x-ui.button type="button" variant="primary" @click="showFormModal = false">Add Product</x-ui.button>
                </x-slot:footer>
            </x-ui.modal>
        </x-ui.card-section>

        <x-ui.card-section title="Confirm dialog" class="sm:col-span-2">
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                A single global dialog (mounted once in <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">layouts.app</code>) triggered from
                anywhere with <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">$store.confirmDialog.open(...)</code>. It has no close (X)
                button — only Cancel/Confirm dismiss it.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button
                    variant="success"
                    @click="$store.confirmDialog.open({
                        title: 'Publish changes?',
                        message: 'Your changes will go live for all users immediately.',
                        variant: 'success',
                        confirmText: 'Publish',
                        onConfirm: () => $store.toast.show({ message: 'Changes published.', type: 'success' }),
                    })"
                >Success</x-ui.button>

                <x-ui.button
                    variant="warning"
                    @click="$store.confirmDialog.open({
                        title: 'Leave without saving?',
                        message: 'You have unsaved changes that will be lost.',
                        variant: 'warning',
                        confirmText: 'Leave',
                        onConfirm: () => $store.toast.show({ message: 'Left without saving.', type: 'warning' }),
                    })"
                >Warning</x-ui.button>

                <x-ui.button
                    variant="danger"
                    @click="$store.confirmDialog.open({
                        title: 'Delete user?',
                        message: 'This action cannot be undone.',
                        variant: 'danger',
                        confirmText: 'Delete',
                        onConfirm: () => $store.toast.show({ message: 'User deleted.', type: 'danger' }),
                    })"
                >Danger</x-ui.button>

                <x-ui.button
                    variant="info"
                    @click="$store.confirmDialog.open({
                        title: 'Enable two-factor auth?',
                        message: 'You will be asked for a code at every login.',
                        variant: 'info',
                        confirmText: 'Enable',
                        onConfirm: () => $store.toast.show({ message: 'Two-factor enabled.', type: 'info' }),
                    })"
                >Info</x-ui.button>

                <x-ui.button
                    variant="primary"
                    @click="$store.confirmDialog.open({
                        title: 'Send invitation?',
                        message: 'An email invite will be sent to this address.',
                        variant: 'primary',
                        confirmText: 'Send',
                        onConfirm: () => $store.toast.show({ message: 'Invitation sent.', type: 'primary' }),
                    })"
                >Primary</x-ui.button>
            </div>
        </x-ui.card-section>
    </div>
@endsection
