@extends('layouts.app')

@section('title', 'Data Table')

@section('header')
    <x-ui.page-header title="Data Table">
        Reference CRUD table — search, filters, sortable columns, bulk-select, pagination, and a row actions popover,
        built from <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.table.*&gt;</code>
        components and a shared
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">dataTable()</code> Alpine factory, so the same structure can be reused for any other CRUD page.
    </x-ui.page-header>
@endsection

@section('content')
    @php
        $statusMeta = [
            'in_stock' => ['label' => 'In Stock', 'variant' => 'success'],
            'low_stock' => ['label' => 'Low Stock', 'variant' => 'warning'],
            'out_of_stock' => ['label' => 'Out of Stock', 'variant' => 'danger'],
        ];

        $categoryVariants = [
            'Electronics' => 'primary',
            'Audio' => 'info',
            'Wearables' => 'success',
            'Accessories' => 'secondary',
        ];

        $categoryOptions = ['Electronics' => 'Electronics', 'Audio' => 'Audio', 'Wearables' => 'Wearables', 'Accessories' => 'Accessories'];
        $statusOptions = ['in_stock' => 'In Stock', 'low_stock' => 'Low Stock', 'out_of_stock' => 'Out of Stock'];

        $rawProducts = [
            ['sku' => 'ELEC-001', 'name' => 'MacBook Pro 16"', 'category' => 'Electronics', 'price' => 2499.00, 'stock' => 15, 'added' => '2024-01-15'],
            ['sku' => 'ELEC-002', 'name' => 'iPhone 15 Pro', 'category' => 'Electronics', 'price' => 999.00, 'stock' => 42, 'added' => '2024-01-22'],
            ['sku' => 'ELEC-003', 'name' => 'AirPods Pro', 'category' => 'Audio', 'price' => 249.00, 'stock' => 78, 'added' => '2024-02-03'],
            ['sku' => 'ELEC-004', 'name' => 'iPad Air', 'category' => 'Electronics', 'price' => 599.00, 'stock' => 23, 'added' => '2024-02-14'],
            ['sku' => 'ELEC-005', 'name' => 'Apple Watch Ultra 2', 'category' => 'Wearables', 'price' => 799.00, 'stock' => 8, 'added' => '2024-03-01'],
            ['sku' => 'ELEC-006', 'name' => 'Samsung Galaxy S24', 'category' => 'Electronics', 'price' => 899.00, 'stock' => 31, 'added' => '2024-03-10'],
            ['sku' => 'ELEC-007', 'name' => 'Sony WH-1000XM5', 'category' => 'Audio', 'price' => 348.00, 'stock' => 0, 'added' => '2024-03-22'],
            ['sku' => 'ELEC-008', 'name' => 'LG 27" 4K Monitor', 'category' => 'Electronics', 'price' => 399.00, 'stock' => 5, 'added' => '2024-04-05'],
            ['sku' => 'ELEC-009', 'name' => 'Logitech MX Master 3S', 'category' => 'Accessories', 'price' => 99.00, 'stock' => 62, 'added' => '2024-04-18'],
            ['sku' => 'ELEC-010', 'name' => 'Keychron K2 Keyboard', 'category' => 'Accessories', 'price' => 149.00, 'stock' => 0, 'added' => '2024-05-02'],
            ['sku' => 'ELEC-011', 'name' => 'Dell XPS 15', 'category' => 'Electronics', 'price' => 1799.00, 'stock' => 12, 'added' => '2024-05-10'],
            ['sku' => 'ELEC-012', 'name' => 'Google Pixel 8 Pro', 'category' => 'Electronics', 'price' => 999.00, 'stock' => 27, 'added' => '2024-05-18'],
            ['sku' => 'ELEC-013', 'name' => 'Bose QuietComfort Ultra', 'category' => 'Audio', 'price' => 429.00, 'stock' => 6, 'added' => '2024-05-25'],
            ['sku' => 'ELEC-014', 'name' => 'Samsung Galaxy Tab S9', 'category' => 'Electronics', 'price' => 799.00, 'stock' => 19, 'added' => '2024-06-01'],
            ['sku' => 'ELEC-015', 'name' => 'Garmin Fenix 7', 'category' => 'Wearables', 'price' => 699.00, 'stock' => 0, 'added' => '2024-06-08'],
            ['sku' => 'ELEC-016', 'name' => 'Anker PowerBank 20K', 'category' => 'Accessories', 'price' => 59.00, 'stock' => 88, 'added' => '2024-06-14'],
            ['sku' => 'ELEC-017', 'name' => 'Razer DeathAdder V3', 'category' => 'Accessories', 'price' => 69.00, 'stock' => 45, 'added' => '2024-06-20'],
            ['sku' => 'ELEC-018', 'name' => 'ASUS ROG Ally', 'category' => 'Electronics', 'price' => 599.00, 'stock' => 9, 'added' => '2024-06-27'],
            ['sku' => 'ELEC-019', 'name' => 'JBL Flip 6', 'category' => 'Audio', 'price' => 129.00, 'stock' => 34, 'added' => '2024-07-03'],
            ['sku' => 'ELEC-020', 'name' => 'Sony A7 IV Camera', 'category' => 'Electronics', 'price' => 2499.00, 'stock' => 4, 'added' => '2024-07-10'],
            ['sku' => 'ELEC-021', 'name' => 'Fitbit Charge 6', 'category' => 'Wearables', 'price' => 159.00, 'stock' => 0, 'added' => '2024-07-16'],
            ['sku' => 'ELEC-022', 'name' => 'Kindle Paperwhite', 'category' => 'Electronics', 'price' => 149.00, 'stock' => 56, 'added' => '2024-07-22'],
            ['sku' => 'ELEC-023', 'name' => 'Nintendo Switch OLED', 'category' => 'Electronics', 'price' => 349.00, 'stock' => 21, 'added' => '2024-07-29'],
            ['sku' => 'ELEC-024', 'name' => 'GoPro Hero 12', 'category' => 'Electronics', 'price' => 399.00, 'stock' => 7, 'added' => '2024-08-04'],
            ['sku' => 'ELEC-025', 'name' => 'Sonos One SL', 'category' => 'Audio', 'price' => 179.00, 'stock' => 18, 'added' => '2024-08-11'],
            ['sku' => 'ELEC-026', 'name' => 'Apple Pencil Pro', 'category' => 'Accessories', 'price' => 129.00, 'stock' => 0, 'added' => '2024-08-17'],
            ['sku' => 'ELEC-027', 'name' => 'Samsung Galaxy Buds3 Pro', 'category' => 'Audio', 'price' => 249.00, 'stock' => 39, 'added' => '2024-08-24'],
            ['sku' => 'ELEC-028', 'name' => 'Microsoft Surface Laptop 6', 'category' => 'Electronics', 'price' => 1299.00, 'stock' => 11, 'added' => '2024-08-30'],
            ['sku' => 'ELEC-029', 'name' => 'Whoop 4.0 Band', 'category' => 'Wearables', 'price' => 239.00, 'stock' => 3, 'added' => '2024-09-05'],
            ['sku' => 'ELEC-030', 'name' => 'Belkin 3-in-1 Charger', 'category' => 'Accessories', 'price' => 149.00, 'stock' => 25, 'added' => '2024-09-12'],
        ];

        $products = collect($rawProducts)->values()->map(function ($product, $index) use ($statusMeta, $categoryVariants) {
            $status = $product['stock'] === 0 ? 'out_of_stock' : ($product['stock'] < 10 ? 'low_stock' : 'in_stock');

            return [
                'id' => $index + 1,
                'sku' => $product['sku'],
                'name' => $product['name'],
                'category' => $product['category'],
                'categoryVariant' => $categoryVariants[$product['category']] ?? 'secondary',
                'price' => $product['price'],
                'stock' => $product['stock'],
                'status' => $status,
                'statusLabel' => $statusMeta[$status]['label'],
                'statusVariant' => $statusMeta[$status]['variant'],
                'added' => $product['added'],
                'addedLabel' => date('M j, Y', strtotime($product['added'])),
            ];
        })->all();

        $emptyForm = [
            'name' => '',
            'sku' => '',
            'category' => 'Electronics',
            'price' => '',
            'stock' => 0,
            'status' => 'in_stock',
        ];
    @endphp

    <div
        class="ui-card"
        x-data="dataTable(@js($products), {
            searchKeys: ['name', 'sku'],
            perPage: 10,
            sortKey: 'id',
            emptyForm: @js($emptyForm),
            entityLabel: 'product',
        })"
    >
        <div class="flex flex-col gap-4 border-b border-gray-200 p-4 sm:p-6 dark:border-gray-800">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-ui.table.search x-model.debounce.300ms="search" placeholder="Search products..." />

                    <x-ui.table.filter
                        label="Category"
                        placeholder="All Categories"
                        :options="$categoryOptions"
                        @change="setFilter('category', $event.target.value)"
                    />

                    <x-ui.table.filter
                        label="Status"
                        placeholder="All Statuses"
                        :options="$statusOptions"
                        @change="setFilter('status', $event.target.value)"
                    />
                </div>

                <x-ui.button variant="primary" @click="openAddModal()">
                    <x-slot:iconLeft><x-icon.plus /></x-slot:iconLeft>
                    Add Product
                </x-ui.button>
            </div>

            <x-ui.table.bulk-actions-bar show="selectedIds.length > 0" count="selectedIds.length" label="selected">
                <x-ui.button size="sm" variant="danger" @click="confirmBulkDelete()">
                    <x-slot:iconLeft><x-icon.trash class="h-4 w-4" /></x-slot:iconLeft>
                    Delete
                </x-ui.button>
                <x-ui.button size="sm" variant="secondary" style="outline" @click="clearSelection()">Clear</x-ui.button>
            </x-ui.table.bulk-actions-bar>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[960px] text-sm">
                <thead class="bg-gray-50 dark:bg-white/[0.03]">
                    <tr>
                        <x-ui.table.checkbox-th
                            model="allSelected"
                            indeterminate="someSelected"
                            @change="toggleSelectAll()"
                        />
                        <x-ui.table.sort-th sort-key="id">ID</x-ui.table.sort-th>
                        <x-ui.table.sort-th sort-key="name">Product</x-ui.table.sort-th>
                        <x-ui.table.sort-th sort-key="category">Category</x-ui.table.sort-th>
                        <x-ui.table.sort-th sort-key="price">Price</x-ui.table.sort-th>
                        <x-ui.table.sort-th sort-key="stock">Stock</x-ui.table.sort-th>
                        <x-ui.table.sort-th sort-key="statusLabel">Status</x-ui.table.sort-th>
                        <x-ui.table.sort-th sort-key="added">Added</x-ui.table.sort-th>
                        <th scope="col" class="px-4 py-3.5 text-right text-xs font-medium tracking-wide text-gray-500 uppercase dark:text-gray-400">Actions</th>
                    </tr>
                </thead>

                <tbody x-ref="tbody" class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($products as $product)
                        <tr
                            data-row-id="{{ $product['id'] }}"
                            x-data="{ menuOpen: false }"
                            x-show="visibleIds.includes({{ $product['id'] }})"
                            x-cloak
                            @table-actions-menu-toggle="menuOpen = $event.detail.open"
                            :class="menuOpen ? 'bg-gray-50 dark:bg-white/[0.02]' : ''"
                            class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]"
                        >
                            <x-ui.table.checkbox-td
                                model="isSelected({{ $product['id'] }})"
                                @change="toggleSelect({{ $product['id'] }})"
                            />
                            <td class="px-4 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">#{{ $product['id'] }}</td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $product['name'] }}</div>
                                <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                    <span>{{ $product['sku'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge :variant="$product['categoryVariant']">{{ $product['category'] }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">${{ number_format($product['price'], 2) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $product['stock'] }}</td>
                            <td class="px-4 py-4">
                                <x-ui.badge :variant="$product['statusVariant']">{{ $product['statusLabel'] }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $product['addedLabel'] }}</td>
                            <td class="px-4 py-4 text-right">
                                {{-- Plain element (not a Blade component tag) so @js() below is
                                     actually expanded by Blade — component-tag attributes are
                                     extracted as literal strings before directive compilation. --}}
                                <div x-data="{ product: @js($product) }" class="inline-flex">
                                    <x-ui.table.actions-menu>
                                        <x-ui.table.actions-menu-item @click="openViewModal(product)">
                                            <x-slot:icon><x-icon.eye class="h-4 w-4" /></x-slot:icon>
                                            View
                                        </x-ui.table.actions-menu-item>
                                        <x-ui.table.actions-menu-item @click="openEditModal(product)">
                                            <x-slot:icon><x-icon.pencil class="h-4 w-4" /></x-slot:icon>
                                            Edit
                                        </x-ui.table.actions-menu-item>
                                        <x-ui.table.actions-menu-item variant="danger" @click="confirmDelete(product)">
                                            <x-slot:icon><x-icon.trash class="h-4 w-4" /></x-slot:icon>
                                            Delete
                                        </x-ui.table.actions-menu-item>
                                    </x-ui.table.actions-menu>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <tr x-show="totalCount === 0">
                        <td colspan="9" class="px-4 py-6">
                            <x-ui.empty-state :bordered="false">
                                <x-slot:icon><x-icon.search class="h-6 w-6" /></x-slot:icon>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">No products found</p>
                                <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">No products match "<span x-text="search"></span>".</p>
                            </x-ui.empty-state>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 p-4 sm:p-6 dark:border-gray-800">
            <x-ui.table.pagination item="products" />
        </div>

        {{-- Add / Edit modal — one form, reused for both by toggling `formMode`. --}}
        <x-ui.modal show="showFormModal" :close-on-backdrop="false" max-width="lg" :title="null">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="formMode === 'add' ? 'Add Product' : 'Edit Product'"></h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="formMode === 'add' ? 'Fill in the details to add a new product.' : 'Update the details for this product.'"></p>

            <form class="mt-4 space-y-5" @submit.prevent="submitForm()">
                <x-form.field label="Product Name" for="product_name">
                    <x-form.input id="product_name" placeholder='e.g. MacBook Pro 16"' x-model="form.name" />
                </x-form.field>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.field label="SKU" for="product_sku">
                        <x-form.input id="product_sku" placeholder="e.g. ELEC-001" x-model="form.sku" />
                    </x-form.field>

                    <x-form.field label="Category" for="product_category">
                        <x-form.select
                            id="product_category"
                            :options="$categoryOptions"
                            selected="Electronics"
                            :show-placeholder="false"
                            x-model="form.category"
                        />
                    </x-form.field>

                    <x-form.field label="Price ($)" for="product_price">
                        <x-form.input id="product_price" type="number" step="0.01" placeholder="0.00" x-model="form.price" />
                    </x-form.field>

                    <x-form.field label="Stock" for="product_stock">
                        <x-form.input id="product_stock" type="number" x-model="form.stock" />
                    </x-form.field>
                </div>

                <x-form.field label="Status" for="product_status">
                    <x-form.select
                        id="product_status"
                        :options="$statusOptions"
                        selected="in_stock"
                        :show-placeholder="false"
                        x-model="form.status"
                    />
                </x-form.field>
            </form>

            <x-slot:footer>
                <x-ui.button type="button" variant="secondary" style="outline" @click="showFormModal = false">Cancel</x-ui.button>
                <x-ui.button type="button" variant="primary" x-bind:disabled="submitting" @click="submitForm()" x-text="submitting ? 'Saving…' : (formMode === 'add' ? 'Add Product' : 'Save Changes')"></x-ui.button>
            </x-slot:footer>
        </x-ui.modal>

        {{-- View modal — read-only snapshot of the selected row. --}}
        <x-ui.modal show="showViewModal" max-width="sm" :title="null">
            <template x-if="viewRow">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="viewRow.name"></h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500" x-text="viewRow.sku"></p>

                    <x-ui.description-list class="mt-5">
                        <x-ui.description-list.item label="Category"><span x-text="viewRow.category"></span></x-ui.description-list.item>
                        <x-ui.description-list.item label="Price"><span x-text="'$' + Number(viewRow.price).toFixed(2)"></span></x-ui.description-list.item>
                        <x-ui.description-list.item label="Stock"><span x-text="viewRow.stock"></span></x-ui.description-list.item>
                        <x-ui.description-list.item label="Status"><span x-text="viewRow.statusLabel"></span></x-ui.description-list.item>
                        <x-ui.description-list.item label="Added"><span x-text="viewRow.addedLabel"></span></x-ui.description-list.item>
                    </x-ui.description-list>
                </div>
            </template>

            <x-slot:footer>
                <x-ui.confirm-delete-button
                    title="'Delete &quot;' + viewRow.name + '&quot;?'"
                    onConfirm="deleteRow(viewRow); showViewModal = false"
                />
                <x-ui.button type="button" variant="secondary" style="outline" @click="showViewModal = false">Close</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    </div>
@endsection
