/**
 * Generic Alpine.data factory backing the reusable calendar date picker at
 * components/form/date-picker.blade.php. Works entirely in `YYYY-MM-DD`
 * strings for the selected/min/max/disabled values (they sort and compare
 * lexicographically, so no timezone-sensitive Date math is needed there);
 * `Date` objects are only used internally to walk the calendar grid.
 *
 *   x-data="datePicker(@js($value), {
 *       format: 'DD/MM/YYYY',
 *       minDate: '2026-01-01',
 *       maxDate: null,
 *       disablePast: false,
 *       disableFuture: false,
 *       disabledDates: ['2026-01-01'],
 *       disabledDaysOfWeek: [0, 6],
 *       firstDayOfWeek: 0,
 *       monthNames: [...],
 *       monthNamesShort: [...],
 *       dayNamesShort: [...],
 *       closeOnSelect: true,
 *   })"
 */
const pad = (n) => String(n).padStart(2, '0');
const toISO = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
const todayISO = () => toISO(new Date());
const firstOfMonth = (date) => new Date(date.getFullYear(), date.getMonth(), 1);
const addMonths = (date, n) => new Date(date.getFullYear(), date.getMonth() + n, 1);
const addYears = (date, n) => new Date(date.getFullYear() + n, date.getMonth(), 1);
const parseISOParts = (iso) => iso.split('-').map(Number);
const dateFromISO = (iso) => {
    const [y, m, d] = parseISOParts(iso);
    return new Date(y, m - 1, d);
};

export default function datePicker(initialValue = null, options = {}) {
    const {
        format = 'DD/MM/YYYY',
        minDate = null,
        maxDate = null,
        disablePast = false,
        disableFuture = false,
        disabledDates = [],
        disabledDaysOfWeek = [],
        firstDayOfWeek = 0,
        monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ],
        monthNamesShort = null,
        dayNamesShort = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        closeOnSelect = true,
    } = options;

    const shortMonths = monthNamesShort ?? monthNames.map((name) => name.slice(0, 3));

    return {
        value: initialValue || null,
        isOpen: false,
        view: 'days', // 'days' | 'months' | 'years'
        viewDate: new Date(),
        focusedDate: null,

        init() {
            const base = this.value || todayISO();
            const [y, m] = parseISOParts(base);
            this.viewDate = new Date(y, m - 1, 1);
            this.focusedDate = base;
        },

        isDateDisabled(date) {
            const iso = toISO(date);
            const today = todayISO();

            if (disablePast && iso < today) return true;
            if (disableFuture && iso > today) return true;
            if (minDate && iso < minDate) return true;
            if (maxDate && iso > maxDate) return true;
            if (disabledDates.includes(iso)) return true;
            if (disabledDaysOfWeek.includes(date.getDay())) return true;

            return false;
        },

        get weekdayLabels() {
            return dayNamesShort.slice(firstDayOfWeek).concat(dayNamesShort.slice(0, firstDayOfWeek));
        },

        get days() {
            const year = this.viewDate.getFullYear();
            const month = this.viewDate.getMonth();
            const startOffset = (firstOfMonth(this.viewDate).getDay() - firstDayOfWeek + 7) % 7;
            const gridStart = new Date(year, month, 1 - startOffset);
            const today = todayISO();

            return Array.from({ length: 42 }, (_, i) => {
                const date = new Date(gridStart.getFullYear(), gridStart.getMonth(), gridStart.getDate() + i);
                const iso = toISO(date);

                return {
                    iso,
                    day: date.getDate(),
                    inCurrentMonth: date.getMonth() === month,
                    isToday: iso === today,
                    isSelected: iso === this.value,
                    isDisabled: this.isDateDisabled(date),
                };
            });
        },

        get months() {
            const year = this.viewDate.getFullYear();
            const now = new Date();
            const selected = this.value ? parseISOParts(this.value) : null;

            return monthNames.map((label, index) => {
                const firstDay = toISO(new Date(year, index, 1));
                const lastDay = toISO(new Date(year, index + 1, 0));

                let isDisabled = false;
                if (minDate && lastDay < minDate) isDisabled = true;
                if (maxDate && firstDay > maxDate) isDisabled = true;
                if (disablePast && (year < now.getFullYear() || (year === now.getFullYear() && index < now.getMonth()))) isDisabled = true;
                if (disableFuture && (year > now.getFullYear() || (year === now.getFullYear() && index > now.getMonth()))) isDisabled = true;

                return {
                    index,
                    label: shortMonths[index] ?? label.slice(0, 3),
                    isSelected: !!selected && selected[0] === year && selected[1] - 1 === index,
                    isDisabled,
                };
            });
        },

        get decadeStart() {
            return Math.floor(this.viewDate.getFullYear() / 12) * 12;
        },

        get years() {
            const start = this.decadeStart;
            const now = new Date();
            const selectedYear = this.value ? parseISOParts(this.value)[0] : null;

            return Array.from({ length: 12 }, (_, i) => {
                const year = start + i;

                let isDisabled = false;
                if (minDate && year < parseISOParts(minDate)[0]) isDisabled = true;
                if (maxDate && year > parseISOParts(maxDate)[0]) isDisabled = true;
                if (disablePast && year < now.getFullYear()) isDisabled = true;
                if (disableFuture && year > now.getFullYear()) isDisabled = true;

                return { value: year, isSelected: year === selectedYear, isDisabled };
            });
        },

        get headerLabel() {
            if (this.view === 'years') {
                return `${this.decadeStart} – ${this.decadeStart + 11}`;
            }
            if (this.view === 'months') {
                return String(this.viewDate.getFullYear());
            }
            return `${monthNames[this.viewDate.getMonth()]} ${this.viewDate.getFullYear()}`;
        },

        formatDisplay(iso) {
            if (!iso) return '';
            const [y, m, d] = parseISOParts(iso);
            const tokens = {
                YYYY: String(y),
                MMMM: monthNames[m - 1] ?? '',
                MMM: shortMonths[m - 1] ?? '',
                MM: pad(m),
                DD: pad(d),
            };
            return format.replace(/YYYY|MMMM|MMM|MM|DD/g, (token) => tokens[token]);
        },

        get displayValue() {
            return this.formatDisplay(this.value);
        },

        open() {
            this.isOpen = true;
            this.view = 'days';
            const base = this.value || this.focusedDate || todayISO();
            const [y, m] = parseISOParts(base);
            this.viewDate = new Date(y, m - 1, 1);
            this.focusedDate = base;
            this.$nextTick(() => this.focusCell());
        },

        close() {
            this.isOpen = false;
        },

        toggle() {
            this.isOpen ? this.close() : this.open();
        },

        cycleView() {
            if (this.view === 'days') this.view = 'months';
            else if (this.view === 'months') this.view = 'years';
        },

        prev() {
            if (this.view === 'days') this.viewDate = addMonths(this.viewDate, -1);
            else if (this.view === 'months') this.viewDate = addYears(this.viewDate, -1);
            else this.viewDate = addYears(this.viewDate, -12);
        },

        next() {
            if (this.view === 'days') this.viewDate = addMonths(this.viewDate, 1);
            else if (this.view === 'months') this.viewDate = addYears(this.viewDate, 1);
            else this.viewDate = addYears(this.viewDate, 12);
        },

        selectDay(cell) {
            if (!cell || cell.isDisabled) return;
            this.value = cell.iso;
            this.focusedDate = cell.iso;
            this.$dispatch('change', { value: cell.iso });
            if (closeOnSelect) this.close();
        },

        selectMonth(index) {
            this.viewDate = new Date(this.viewDate.getFullYear(), index, 1);
            this.view = 'days';
        },

        selectYear(year) {
            this.viewDate = new Date(year, this.viewDate.getMonth(), 1);
            this.view = 'months';
        },

        goToday() {
            const iso = todayISO();
            const [y, m] = parseISOParts(iso);
            this.viewDate = new Date(y, m - 1, 1);
            this.view = 'days';
            this.focusedDate = iso;

            if (!this.isDateDisabled(new Date())) {
                this.value = iso;
                this.$dispatch('change', { value: iso });
                if (closeOnSelect) this.close();
            }
        },

        clear() {
            this.value = null;
            this.$dispatch('change', { value: null });
        },

        focusCell() {
            this.$refs.dayGrid?.querySelector(`[data-iso="${this.focusedDate}"]`)?.focus();
        },

        moveFocus(deltaDays) {
            const [y, m, d] = parseISOParts(this.focusedDate);
            const next = new Date(y, m - 1, d + deltaDays);
            this.focusedDate = toISO(next);

            if (next.getMonth() !== this.viewDate.getMonth() || next.getFullYear() !== this.viewDate.getFullYear()) {
                this.viewDate = firstOfMonth(next);
            }

            this.$nextTick(() => this.focusCell());
        },

        clampFocusToViewMonth() {
            const [, , d] = parseISOParts(this.focusedDate);
            const year = this.viewDate.getFullYear();
            const month = this.viewDate.getMonth();
            const lastDay = new Date(year, month + 1, 0).getDate();
            this.focusedDate = toISO(new Date(year, month, Math.min(d, lastDay)));
            this.$nextTick(() => this.focusCell());
        },

        onKeydown(e) {
            if (!this.isOpen) return;

            if (e.key === 'Escape') {
                e.preventDefault();
                this.close();
                this.$refs.trigger?.focus();
                return;
            }

            if (this.view !== 'days') return;

            const dow = (date) => (date.getDay() - firstDayOfWeek + 7) % 7;

            const handlers = {
                ArrowLeft: () => this.moveFocus(-1),
                ArrowRight: () => this.moveFocus(1),
                ArrowUp: () => this.moveFocus(-7),
                ArrowDown: () => this.moveFocus(7),
                Home: () => this.moveFocus(-dow(dateFromISO(this.focusedDate))),
                End: () => this.moveFocus(6 - dow(dateFromISO(this.focusedDate))),
                PageUp: () => {
                    this.viewDate = e.shiftKey ? addYears(this.viewDate, -1) : addMonths(this.viewDate, -1);
                    this.clampFocusToViewMonth();
                },
                PageDown: () => {
                    this.viewDate = e.shiftKey ? addYears(this.viewDate, 1) : addMonths(this.viewDate, 1);
                    this.clampFocusToViewMonth();
                },
                Enter: () => this.selectDay(this.days.find((c) => c.iso === this.focusedDate)),
                ' ': () => this.selectDay(this.days.find((c) => c.iso === this.focusedDate)),
            };

            const handler = handlers[e.key];
            if (handler) {
                e.preventDefault();
                handler();
            }
        },
    };
}
