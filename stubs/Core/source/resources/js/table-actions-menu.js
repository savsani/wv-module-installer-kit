/**
 * Backs <x-ui.table.actions-menu> (the row "⋮" menu on data table pages).
 * The menu is teleported to <body> and positioned with fixed coordinates
 * computed from the trigger button's bounding rect, so it always renders as
 * a true popover — never clipped by the table's overflow-x-auto wrapper —
 * and flips above the trigger when there isn't room below (e.g. the last
 * row of a table), all without the page needing to scroll.
 *
 * Dispatches a `table-actions-menu-toggle` event (bubbling, detail: { open })
 * from the trigger button on open/close, so an ancestor `<tr>` can highlight
 * itself while the menu for that row is open — see examples/data-table.blade.php.
 * Dispatched via a native `dispatchEvent` on the trigger (not Alpine's
 * `$dispatch`) because close() is also called from handlers living on the
 * teleported popover, which Alpine has moved to <body> — `$dispatch` fires
 * from whatever element the calling expression is bound to, so from there
 * it would never bubble up to the row.
 */
export default function tableActionsMenu() {
    return {
        open: false,
        dropUp: false,
        coords: { top: 0, left: 0 },
        trigger: null,

        notify(open) {
            this.trigger?.dispatchEvent(new CustomEvent('table-actions-menu-toggle', { bubbles: true, detail: { open } }));
        },

        toggle(event) {
            if (this.open) {
                this.close();
                return;
            }

            this.trigger = event.currentTarget;
            this.open = true;
            this.notify(true);
            this.$nextTick(() => this.position());
        },

        position() {
            if (!this.trigger || !this.$refs.menu) return;

            const rect = this.trigger.getBoundingClientRect();
            const menuRect = this.$refs.menu.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;

            this.dropUp = spaceBelow < menuRect.height + 12 && spaceAbove > spaceBelow;

            const top = this.dropUp ? rect.top - menuRect.height - 8 : rect.bottom + 8;
            const left = Math.min(rect.right - menuRect.width, window.innerWidth - menuRect.width - 8);

            this.coords = { top: Math.max(8, top), left: Math.max(8, left) };
        },

        close() {
            if (!this.open) return;

            this.open = false;
            this.notify(false);
        },
    };
}
