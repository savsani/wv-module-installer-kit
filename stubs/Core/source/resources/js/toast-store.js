/**
 * Alpine store backing the toast notification stack, read by
 * components/ui/toast.blade.php via $store.toast.
 *
 *   $store.toast.show({ title, message, type: 'success' | 'error' | ..., duration })
 */
export default {
    items: [],

    show({ title = null, message = '', type = 'success', duration = 3000 } = {}) {
        const id = Date.now() + Math.random();
        this.items = [...this.items, { id, title, message, type }];
        if (duration > 0) setTimeout(() => this.dismiss(id), duration);
    },

    dismiss(id) {
        this.items = this.items.filter((i) => i.id !== id);
    },
};
