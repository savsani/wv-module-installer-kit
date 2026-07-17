/**
 * Generic Alpine.data factory backing the reusable data table pattern shown at
 * examples/data-table.blade.php. Rows are rendered once by Blade (so real
 * columns — including <x-ui.badge> — always reflect true server markup); this
 * factory only computes which of those already-rendered rows are visible for
 * the current search/page (`visibleIds`) and, on sort, physically reorders
 * the <tr> elements inside the `tbody` ref to match. No row is ever
 * re-templated client-side, so any Blade component used per-row keeps working
 * unmodified.
 *
 *   x-data="dataTable(@js($rows), {
 *       searchKeys: ['name', 'sku'],
 *       perPage: 10,
 *       sortKey: 'id',
 *       entityLabel: 'product',
 *       onDelete: (row) => fetch(`/products/${row.id}`, { method: 'DELETE', headers: csrfHeaders }),
 *       onSubmit: (form, mode) => fetch(mode === 'add' ? '/products' : `/products/${form.id}`, {
 *           method: mode === 'add' ? 'POST' : 'PUT',
 *           headers: { 'Content-Type': 'application/json', ...csrfHeaders },
 *           body: JSON.stringify(form),
 *       }).then((r) => r.json()),
 *   })"
 *
 * `onDelete`/`onSubmit` are the only backend-specific pieces — everything else
 * (search, sort, pagination, filtering, bulk-select, modal state) is reused
 * unmodified across tables. Both are optional: without them the table
 * behaves as a client-only demo (delete just hides the row, submit just
 * toasts), which is what the examples/data-table.blade.php page relies on
 * since it has no backend.
 *
 * Column filters (<x-ui.table.filter>) call setFilter(key, value); rows are
 * kept when row[key] matches every active filter. A key ending in "_min" or
 * "_max" filters by numeric range instead of exact match — e.g.
 * setFilter('created_at_ts_min', someTimestamp) keeps rows whose
 * row.created_at_ts is >= someTimestamp, against the base field name with
 * the suffix stripped. Used for date-range filters (see admin/activity-log).
 * Bulk-select (<x-ui.table.checkbox-th>/checkbox-td> +
 * <x-ui.table.bulk-actions-bar>) is driven by
 * selectedIds/toggleSelect/toggleSelectAll/confirmBulkDelete.
 */
export default function dataTable(initialRows = [], options = {}) {
    const {
        searchKeys = [],
        perPage = 10,
        sortKey = null,
        sortDir = 'asc',
        emptyForm = {},
        entityLabel = 'item',
        rowLabel = (row) => row.name ?? row.id,
        onDelete = null,
        onSubmit = null,
    } = options;

    return {
        rows: initialRows,
        deletedIds: [],
        deleting: false,
        submitting: false,
        search: '',
        filters: {},
        selectedIds: [],
        sortKey,
        sortDir,
        page: 1,
        perPage,
        perPageOptions: [10, 20, 50, 100, 'all'],

        showFormModal: false,
        formMode: 'add',
        form: { ...emptyForm },
        editingId: null,

        showViewModal: false,
        viewRow: null,

        init() {
            this.$watch('search', () => {
                this.page = 1;
            });
            this.$watch('perPage', () => {
                this.page = 1;
            });
            this.$watch('sortKey', () => this.reorderRows());
            this.$watch('sortDir', () => this.reorderRows());
        },

        get activeRows() {
            return this.rows.filter((row) => !this.deletedIds.includes(row.id));
        },

        get filteredRows() {
            const query = this.search.trim().toLowerCase();

            let rows = query
                ? this.activeRows.filter((row) => searchKeys.some((key) => String(row[key] ?? '').toLowerCase().includes(query)))
                : this.activeRows;

            Object.entries(this.filters).forEach(([key, value]) => {
                if (value === null || value === undefined || value === '') return;

                if (key.endsWith('_min')) {
                    const field = key.slice(0, -4);
                    rows = rows.filter((row) => Number(row[field]) >= Number(value));
                } else if (key.endsWith('_max')) {
                    const field = key.slice(0, -4);
                    rows = rows.filter((row) => Number(row[field]) <= Number(value));
                } else {
                    rows = rows.filter((row) => String(row[key]) === String(value));
                }
            });

            return rows;
        },

        get sortedRows() {
            if (!this.sortKey) return this.filteredRows;

            const key = this.sortKey;
            const dir = this.sortDir === 'asc' ? 1 : -1;

            return [...this.filteredRows].sort((a, b) => {
                const av = a[key];
                const bv = b[key];

                if (typeof av === 'number' && typeof bv === 'number') {
                    return (av - bv) * dir;
                }

                return String(av ?? '').localeCompare(String(bv ?? '')) * dir;
            });
        },

        get totalCount() {
            return this.filteredRows.length;
        },

        get totalPages() {
            if (this.perPage === 'all') return 1;

            return Math.max(1, Math.ceil(this.totalCount / this.perPage));
        },

        get pagedRows() {
            if (this.perPage === 'all') return this.sortedRows;

            const start = (this.page - 1) * this.perPage;
            return this.sortedRows.slice(start, start + this.perPage);
        },

        get visibleIds() {
            return this.pagedRows.map((row) => row.id);
        },

        get selectedCount() {
            return this.selectedIds.length;
        },

        get allSelected() {
            return this.pagedRows.length > 0 && this.pagedRows.every((row) => this.selectedIds.includes(row.id));
        },

        get someSelected() {
            return this.selectedCount > 0 && !this.allSelected;
        },

        get rangeStart() {
            if (this.totalCount === 0) return 0;
            if (this.perPage === 'all') return 1;

            return (this.page - 1) * this.perPage + 1;
        },

        get rangeEnd() {
            if (this.perPage === 'all') return this.totalCount;

            return Math.min(this.page * this.perPage, this.totalCount);
        },

        get pageNumbers() {
            const total = this.totalPages;
            const current = this.page;
            const pages = [];

            for (let i = 1; i <= total; i++) {
                if (i === 1 || i === total || Math.abs(i - current) <= 1) {
                    pages.push(i);
                } else if (pages[pages.length - 1] !== '…') {
                    pages.push('…');
                }
            }

            return pages;
        },

        toggleSort(key) {
            if (this.sortKey === key) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = key;
                this.sortDir = 'asc';
            }
        },

        reorderRows() {
            this.$nextTick(() => {
                const tbody = this.$refs.tbody;
                if (!tbody) return;

                this.sortedRows.forEach((row) => {
                    const el = tbody.querySelector(`[data-row-id="${row.id}"]`);
                    if (el) tbody.appendChild(el);
                });
            });
        },

        setPerPage(value) {
            this.perPage = value === 'all' ? 'all' : Number(value);
        },

        setFilter(key, value) {
            this.filters = { ...this.filters, [key]: value };
            this.page = 1;
        },

        isSelected(id) {
            return this.selectedIds.includes(id);
        },

        toggleSelect(id) {
            this.selectedIds = this.isSelected(id) ? this.selectedIds.filter((selectedId) => selectedId !== id) : [...this.selectedIds, id];
        },

        toggleSelectAll() {
            const pageIds = this.pagedRows.map((row) => row.id);
            this.selectedIds = this.allSelected
                ? this.selectedIds.filter((id) => !pageIds.includes(id))
                : [...new Set([...this.selectedIds, ...pageIds])];
        },

        clearSelection() {
            this.selectedIds = [];
        },

        goToPage(n) {
            if (n < 1 || n > this.totalPages) return;
            this.page = n;
        },

        prevPage() {
            this.goToPage(this.page - 1);
        },

        nextPage() {
            this.goToPage(this.page + 1);
        },

        openAddModal() {
            this.formMode = 'add';
            this.form = { ...emptyForm };
            this.editingId = null;
            this.showFormModal = true;
        },

        openEditModal(row) {
            this.formMode = 'edit';
            this.form = { ...row };
            this.editingId = row.id;
            this.showFormModal = true;
        },

        openViewModal(row) {
            this.viewRow = row;
            this.showViewModal = true;
        },

        async submitForm() {
            const isAdd = this.formMode === 'add';
            this.submitting = true;

            try {
                const saved = onSubmit ? await onSubmit(this.form, this.formMode) : this.form;

                if (onSubmit) {
                    this.rows = isAdd ? [...this.rows, saved] : this.rows.map((row) => (row.id === this.editingId ? { ...row, ...saved } : row));
                }

                this.$store.toast.show({
                    message: isAdd ? `"${rowLabel(saved)}" was added.` : `"${rowLabel(saved)}" was updated.`,
                    type: 'success',
                });
                this.showFormModal = false;
            } catch (error) {
                this.$store.toast.show({ message: error?.message ?? `Failed to save ${entityLabel}.`, type: 'danger' });
            } finally {
                this.submitting = false;
            }
        },

        removeRow(id) {
            this.deletedIds = [...this.deletedIds, id];
            this.selectedIds = this.selectedIds.filter((selectedId) => selectedId !== id);
            if (this.page > this.totalPages) this.page = this.totalPages;
        },

        confirmDelete(row) {
            this.$store.confirmDialog.open({
                title: `Delete ${entityLabel}?`,
                message: `Are you sure you want to delete '${rowLabel(row)}'? This action cannot be undone.`,
                variant: 'danger',
                confirmText: 'Delete',
                onConfirm: () => this.deleteRow(row),
            });
        },

        async deleteRow(row) {
            this.deleting = true;

            try {
                if (onDelete) {
                    await onDelete(row);
                }
                this.removeRow(row.id);
                this.$store.toast.show({ message: `"${rowLabel(row)}" was deleted.`, type: 'danger' });
            } catch (error) {
                this.$store.toast.show({ message: error?.message ?? `Failed to delete "${rowLabel(row)}".`, type: 'danger' });
            } finally {
                this.deleting = false;
            }
        },

        confirmBulkDelete() {
            const count = this.selectedCount;
            const noun = count === 1 ? entityLabel : `${entityLabel}s`;

            this.$store.confirmDialog.open({
                title: `Delete ${count} ${noun}?`,
                message: `Are you sure you want to delete ${count} selected ${noun}? This action cannot be undone.`,
                variant: 'danger',
                confirmText: 'Delete',
                onConfirm: () => this.bulkDelete(),
            });
        },

        async bulkDelete() {
            const ids = [...this.selectedIds];
            const rowsToDelete = this.rows.filter((row) => ids.includes(row.id));
            this.deleting = true;

            try {
                if (onDelete) {
                    await Promise.all(rowsToDelete.map((row) => onDelete(row)));
                }
                ids.forEach((id) => this.removeRow(id));
                this.$store.toast.show({ message: `${ids.length} ${ids.length === 1 ? entityLabel : `${entityLabel}s`} deleted.`, type: 'danger' });
            } catch (error) {
                this.$store.toast.show({ message: error?.message ?? `Failed to delete selected ${entityLabel}s.`, type: 'danger' });
            } finally {
                this.deleting = false;
            }
        },
    };
}
