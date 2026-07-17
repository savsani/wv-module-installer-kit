/**
 * Alpine store backing the shared confirm dialog, read independently by
 * components/ui/confirm-dialog.blade.php and
 * components/ui/confirm-delete-button.blade.php via $store.confirmDialog.
 *
 *   $store.confirmDialog.open({ title, message, variant, confirmText, cancelText, onConfirm })
 */
export default {
    show: false,
    title: 'Are you sure?',
    message: '',
    variant: 'danger', // success | warning | danger | info | primary
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    onConfirm: null,

    open({ title = 'Are you sure?', message = '', variant = 'danger', confirmText = 'Confirm', cancelText = 'Cancel', onConfirm = null } = {}) {
        this.title = title;
        this.message = message;
        this.variant = variant;
        this.confirmText = confirmText;
        this.cancelText = cancelText;
        this.onConfirm = onConfirm;
        this.show = true;
    },

    confirm() {
        const callback = this.onConfirm;
        this.show = false;
        if (typeof callback === 'function') callback();
    },

    close() {
        this.show = false;
    },
};
